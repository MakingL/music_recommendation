# -*- coding: utf-8 -*-
# @Time    : 2018/5/16 10:29
# @File    : make_recommendation.py
import os
import pickle

import numpy as np
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
        dir_data = os.path.join(os.path.dirname(__file__), "dl_data")
        dir_feature_vector_recommendation = os.path.join(dir_data, 'feature_arrays_recommendation')
        path_track_information = os.path.join(dir_data, 'final_data.pkl')
        path_recommend_feature = os.path.join(dir_data, 'feature_arrays_recommendation', '{}.npy'.format(song_id))
        path_data_genre_feature = os.path.join(dir_data, "all_feature_arrays_dataset", "feature")
        path_data_genre_song_id = os.path.join(dir_data, "all_feature_arrays_dataset", "song_id")

        self.dir_feature_vector_recommendation = dir_feature_vector_recommendation
        self.path_track_information = path_track_information
        self.path_recommend_feature = path_recommend_feature
        self.path_data_genre_feature = path_data_genre_feature
        self.path_data_genre_song_id = path_data_genre_song_id

    def recommend(self, genre_name):
        def delete_file(file_path):
            # helper function to delete files no longer needed
            os.remove(file_path)

        npy_list = list()
        song_ids = list()
        genre_name = genre_name.lower()
        genre_name = genre_name.replace('/', '_').replace('&', '_').replace(' ', '_')
        path_genre_tracks_feature = os.path.join(self.path_data_genre_feature, "{}.npy".format(genre_name))
        path_genre_tracks_song_id = os.path.join(self.path_data_genre_song_id, "{}.pkl".format(genre_name))
        feature_data_set_array = np.load(path_genre_tracks_feature)
        song_id_data_set_array = pickle.load(
            open(path_genre_tracks_song_id, 'rb'))

        npy_list.append(feature_data_set_array)
        song_ids.extend(list(song_id_data_set_array))

        # 将待推荐的歌曲加进列表
        npy = np.load(self.path_recommend_feature)
        npy_list.append(npy)

        # 将待推荐的歌曲的特征向量删除
        delete_file(self.path_recommend_feature)

        selected_song_id = os.path.basename(self.path_recommend_feature.replace('.npy', ''))
        song_ids.append(selected_song_id)

        # 将所有歌曲的特征向量拼接成一个大的numpy矩阵
        np_arrays = np.concatenate(npy_list)

        # 计算歌曲之间的余弦相似度
        songs_similarity = cosine_similarity(np_arrays)
        song_distance = pd.DataFrame(songs_similarity, columns=song_ids)
        song_distance.index = song_distance.columns

        # 导入歌曲详细信息
        with open(self.path_track_information, "rb") as input_file:
            tracks_information = pickle.load(input_file)

        # 得到余弦相似度最高的结果
        recommendations = song_distance[str(selected_song_id)].sort_values(ascending=False).reset_index()

        # 给出 TOP 推荐
        top_5_recs = list(recommendations.head(6)['index'])[1:]
        d_list = []
        for r in top_5_recs:
            df = tracks_information[tracks_information['id'] == int(r)]
            d = df.to_dict(orient='records')
            d_list.append(d)

        return d_list
