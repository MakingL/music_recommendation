# -*- coding: utf-8 -*-
# @Time    : 2018/5/20 11:24
# @File    : cf_recommendation.py
from __future__ import (absolute_import, division, print_function,
                        unicode_literals)
from collections import defaultdict

import time
import sys
import os
import pymysql
from surprise import Dataset
from surprise import Reader
from surprise import BaselineOnly
from surprise import KNNBasic
from surprise import KNNBaseline
from heapq import nlargest


def get_top_n(predictions, n=10):
    # First map the predictions to each user.
    top_n = defaultdict(list)
    for uid, iid, true_r, est, _ in predictions:
        top_n[uid].append((iid, est))

    # Then sort the predictions for each user and retrieve the k highest ones.
    for uid, user_ratings in top_n.items():
        # user_ratings.sort(key=lambda x: x[1], reverse=True)
        # top_n[uid] = user_ratings[:n]
        top_n[uid] = nlargest(n, user_ratings, key=lambda s: s[1])

    return top_n

class PredictionSet():

    def __init__(self, algo, trainset, user_raw_id=None, k=40):
        self.algo = algo
        self.trainset = trainset
        self.k = k
        if user_raw_id is not None:
            self.r_uid = user_raw_id
            self.i_uid = trainset.to_inner_uid(user_raw_id)
            self.knn_userset = self.algo.get_neighbors(self.i_uid, self.k)
            user_items = set([j for (j, _) in self.trainset.ur[self.i_uid]])
            self.neighbor_items = set()
            for nnu in self.knn_userset:
                for (j, _) in trainset.ur[nnu]:
                    if j not in user_items:
                        self.neighbor_items.add(j)

    def build_anti_testset(self, fill=None):
        fill = self.trainset.global_mean if fill is None else float(fill)

        anti_testset = []
        for u in self.trainset.all_users():
            neighbor_items = set()
            neighbor_items.clear()
            u_neighbors = self.algo.get_neighbors(u, self.k)
            for nnu in u_neighbors:
                for (j, _) in self.trainset.ur[nnu]:
                    neighbor_items.add(j)
            user_items = set([j for (j, _) in self.trainset.ur[u]])
            anti_testset += [(self.trainset.to_raw_uid(u), self.trainset.to_raw_iid(i), fill) for
                             i in neighbor_items if
                             i not in user_items]
        return anti_testset

    # def user_build_anti_testset(self, fill=None):
    #     fill = self.trainset.global_mean if fill is None else float(fill)

    #     anti_testset = []
    #     anti_testset += [(self.r_uid, self.trainset.to_raw_iid(i), fill) for
    #                      i in self.neighbor_items]
    #     return anti_testset
    def user_build_anti_testset(self, fill=None):
        """
            为单个用户生成测试集
        """
        fill = self.trainset.global_mean if fill is None else float(fill)

        anti_testset = []
        user_items = set([j for (j, _) in self.trainset.ur[self.i_uid]])
        anti_testset += [(self.r_uid, self.trainset.to_raw_iid(i), fill) for
                         i in self.neighbor_items if
                         i not in user_items]
        return anti_testset

def user_build_anti_testset(trainset, user_raw_id, fill=None):
    """
        为用户生成测试集 --- 并不基于KNN
    """
    fill = trainset.global_mean if fill is None else float(fill)

    i_uid = trainset.to_inner_uid(user_raw_id)
    anti_testset = []

    user_items = set([j for (j, _) in trainset.ur[i_uid]])
    anti_testset += [(user_raw_id, trainset.to_raw_iid(i), fill) for
                     i in trainset.all_items() if
                     i not in user_items]
    return anti_testset



# ================= surprise 推荐部分 ====================
def collaborative_fitlering( raw_uid):
    # =============== 数据预处理 ===========================
    # 将数据库中的所有数据读取转换到文件
    # dir_data = '/www/wwwroot/music_recommender/page/cf_recommendation/cf_data'
    dir_data = './collaborative_filtering/cf_data'
    file_path = '{}/dataset_user_5.txt'.format(dir_data)
    if not os.path.exists(dir_data):
        os.makedirs(dir_data)

    # 数据库操作
    # 打开数据库连接
    db = pymysql.connect("mysql",
                         "music_system",
                         "music_system",
                         "music_recommender",
                         charset='utf8')

    # 使用 cursor() 方法创建一个游标对象 cursor
    cursor = db.cursor()

    sql = """SELECT uid, song_id, rating
              FROM user_rating
               WHERE 1"""
    cursor.execute(sql)
    results = cursor.fetchall()
    with open(file_path, "w+") as data_f:
        for result in results:
            uid, song_id, rating = result

            data_f.writelines("{}\t{}\t{}\n".format(uid, song_id, rating))

    if not os.path.exists(file_path):
        raise IOError("Dataset file is not exists!")

    # ===========  cf recommend ==================
    # 导入数据
    reader = Reader(line_format='user item rating', sep='\t')
    data = Dataset.load_from_file(file_path, reader=reader)

    # 所有数据生成训练集
    trainset = data.build_full_trainset()

    # ================= BaselineOnly  ==================
    # start = time.clock()

    bsl_options = {'method': 'sgd',
                    'learning_rate': 0.0005,
                 }
    algo_BaselineOnly = BaselineOnly(bsl_options=bsl_options)
    algo_BaselineOnly.fit(trainset)

    # 获得推荐结果
    rset = user_build_anti_testset(trainset, raw_uid)
    predictions = algo_BaselineOnly.test(rset)
    top_n_baselineonly = get_top_n(predictions, n=5)

    # end = time.clock()
    # print("user-50NN --- BaselineOnly 耗时： %.2fs\n" % (end-start))
    # print("BaselineOnly 推荐结果:{}\n".format(top_n_baselineonly))

    # ================= KNNBasic  ==================
    sim_options = {'name': 'pearson', 'user_based': True}
    algo_KNNBasic = KNNBasic(sim_options=sim_options)
    algo_KNNBasic.fit(trainset)

    # 获得推荐结果  ---  只考虑 knn 用户的
    # start = time.clock()
    predictor = PredictionSet(algo_KNNBasic, trainset, raw_uid)
    knn_anti_set = predictor.user_build_anti_testset()
    predictions = algo_KNNBasic.test(knn_anti_set)
    top_n_knnbasic = get_top_n(predictions, n=5)

    # end = time.clock()
    # print("user-50NN --- KNNBasic 耗时： %.2fs\n" % (end-start))
    # print("KNNBasic 推荐结果:{}\n".format(top_n_knnbasic))

    # ================= KNNBaseline  ==================
    sim_options = {'name': 'pearson_baseline', 'user_based': True}
    algo_KNNBaseline = KNNBaseline(sim_options=sim_options)
    algo_KNNBaseline.fit(trainset)

    # 获得推荐结果  ---  只考虑 knn 用户的
    # start = time.clock()
    predictor = PredictionSet(algo_KNNBaseline, trainset, raw_uid)
    knn_anti_set = predictor.user_build_anti_testset()
    predictions = algo_KNNBaseline.test(knn_anti_set)
    top_n_knnbaseline = get_top_n(predictions, n=5)

    # end = time.clock()
    # print("user-50NN --- KNNBaseline 耗时： %.2fs\n" % (end-start))
    # print("KNNBaseline 推荐结果:{}\n".format(top_n_knnbaseline))


    # =============== 按比例生成推荐结果 ==================
    recommendset = set()
    for results in [top_n_baselineonly, top_n_knnbasic, top_n_knnbaseline]:
        for key in results.keys():
            for recommendations in results[key]:
                iid, rating = recommendations
                recommendset.add(iid)

    items_baselineonly = set()
    for key in top_n_baselineonly.keys():
        for recommendations in top_n_baselineonly[key]:
            iid, rating = recommendations
            items_baselineonly.add(iid)

    items_knnbasic = set()
    for key in top_n_knnbasic.keys():
        for recommendations in top_n_knnbasic[key]:
            iid, rating = recommendations
            items_knnbasic.add(iid)

    items_knnbaseline = set()
    for key in top_n_knnbaseline.keys():
        for recommendations in top_n_knnbaseline[key]:
            iid, rating = recommendations
            items_knnbaseline.add(iid)

    rank = dict()
    for recommendation in recommendset:
        if recommendation not in rank:
            rank[recommendation] = 0
        if recommendation in items_baselineonly:
            rank[recommendation] += 1
        if recommendation in items_knnbasic:
            rank[recommendation] += 1
        if recommendation in items_knnbaseline:
            rank[recommendation] += 1

    max_rank = max(rank, key=lambda s: rank[s])
    if max_rank == 1:
        # print(items_baselineonly)
        return items_baselineonly
    else:
        result = nlargest(5, rank, key=lambda s: rank[s])
        # print(result)
        return result
        # print("排名结果: {}".format(result))


    # t = {"ddd":5, "dsw":3, "dddw":1, "sdfd":88}
    # result = nlargest(2, t, key=lambda s: t[s])
