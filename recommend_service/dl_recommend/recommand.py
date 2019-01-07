# -*- coding: utf-8 -*-
# @Time    : 2018/5/16 12:12
# @File    : recommend.py
import os

from dl_recommend import get_genre, get_feature_vector, make_recommendation


class Recommendation(object):
    """docstring for Recommendation
    推荐管理类
    """

    def __init__(self, path_track):
        super(Recommendation, self).__init__()
        self.path_track = path_track
        base_path_track = os.path.basename(path_track)
        self.song_id = base_path_track.split(".")[0]
        self.get_genre = get_genre.GetGenre(self.path_track)
        self.get_feature_vector = get_feature_vector.GetFeatureVector(self.song_id)
        self.make_recommendation = make_recommendation.MakeRecommendation(self.song_id)

    def recommend_similar_track(self):
        track_genre = self.get_genre.analyse_track()
        track_genre_name, confidence = track_genre
        self.get_feature_vector.get_feature()
        return track_genre, self.make_recommendation.recommend(track_genre_name.lower())
