# -*- coding: utf-8 -*-
# @Time    : 2018/5/16 10:29
# @File    : make_recommendation.py
import os
import glob
import numpy as np
import pickle
import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity

class MakeRecommendation(object):
    """docstring for MakeRecommendation
        根据歌曲的平均特征向量
        计算与已知数据集的相似度
        将相似度最高的歌曲信息
        作为推荐结果
    """
    def __init__(self, song_id):
        super(MakeRecommendation, self).__init__()
        # 所有数据存放的目录
        DIR_DATA_RUNING = "./data"
        # 待预测的歌曲信息存放目录
        # DIR_DATA_PREDICTION = '{}/prediction_track'.format(DIR_DATA_RUNING)
        DIR_FEATURE_VECTOR_RECOMMENDATION = '{}/feature_arrays_recommendation'.format(DIR_DATA_RUNING)
        PATH_TRACK_INFORMATION = '{}/final_data.pkl'.format(DIR_DATA_RUNING)
        PATH_RECOMMEND_FEATURE = './data/feature_arrays_recommendation/{}.npy'.format(song_id)
        PATH_DATASET_GENER_FEATURE = "./data/all_feature_arrays_dataset/feature"
        PATH_DATASET_GENER_SONG_ID = "./data/all_feature_arrays_dataset/song_id"

        self.DIR_FEATURE_VECTOR_RECOMMENDATION = DIR_FEATURE_VECTOR_RECOMMENDATION
        self.PATH_TRACK_INFORMATION = PATH_TRACK_INFORMATION
        self.PATH_RECOMMEND_FEATURE = PATH_RECOMMEND_FEATURE
        self.PATH_DATASET_GENER_FEATURE = PATH_DATASET_GENER_FEATURE
        self.PATH_DATASET_GENER_SONG_ID = PATH_DATASET_GENER_SONG_ID

    def recommend(self, gener_name):
        # helper function to delete files no longer needed
        def delete_file(file_path):
            os.remove(file_path)

        npy_list = list()
        song_ids = list()
        gener_name = gener_name.lower()
        gener_name = gener_name.replace('/', '_').replace('&', '_').replace(' ', '_')
        path_gener_tracks_feature = "{}/{}.npy".format(self.PATH_DATASET_GENER_FEATURE, gener_name)
        path_gener_tracks_song_id = "{}/{}.pkl".format(self.PATH_DATASET_GENER_SONG_ID, gener_name)
        feature_dataset_array = np.load(path_gener_tracks_feature)
        songID_dataset_array = pickle.load(
            open(path_gener_tracks_song_id,
            'rb'))

        npy_list.append(feature_dataset_array)
        song_ids.extend(list(songID_dataset_array))

        # 将待推荐的歌曲加进列表
        npy = np.load(self.PATH_RECOMMEND_FEATURE)
        npy_list.append(npy)

        # 将待推荐的歌曲的特征向量删除
        delete_file(self.PATH_RECOMMEND_FEATURE)

        selected_song_id = self.PATH_RECOMMEND_FEATURE.replace('.npy','').split('/')[-1]
        song_ids.append(selected_song_id)

        # 将所有歌曲的特征向量拼接成一个大的numpy矩阵
        np_arrays = np.concatenate(npy_list)

        # 计算歌曲之间的余弦相似度
        songs_similarity = cosine_similarity(np_arrays)
        song_distance = pd.DataFrame(songs_similarity, columns=song_ids)
        song_distance.index = song_distance.columns

        # 导入歌曲详细信息
        with open(self.PATH_TRACK_INFORMATION, "rb") as input_file:
            tracks_information = pickle.load(input_file)

        # 得到余弦相似度最高的结果
        recommendations = song_distance[str(selected_song_id)].sort_values(ascending=False).reset_index()

        top_5_recs = list(recommendations.head(6)['index'])[1:]
        d_list = []
        for r in top_5_recs:
            df = tracks_information[tracks_information['id'] == int(r)]
            d = df.to_dict(orient='records')
            d_list.append(d)

        # pprint(d_list)
        return d_list
