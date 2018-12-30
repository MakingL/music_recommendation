# -*- coding: utf-8 -*-
# @Time    : 2018/5/16 12:12
# @File    : recommand.py
from dl_recommend import get_gener, get_feature_vector, make_recommendation


class Recommandation(object):
    """docstring for Recommandation
    推荐管理类
    """
    def __init__(self, path_track):
        super(Recommandation, self).__init__()
        self.path_track = path_track
        self.song_id = path_track.split("/")[-1].split(".")[0]
        self.get_gener = get_gener.GetGener(self.path_track)
        self.get_feature_vector = get_feature_vector.GetFeatureVector(self.song_id)
        self.make_recommendation = make_recommendation.MakeRecommendation(self.song_id)

    def recommend_similar_track(self):
        track_gener = self.get_gener.analyse_track()
        track_gener_name, confidence = track_gener
        self.get_feature_vector.get_feature()
        return track_gener, self.make_recommendation.recommend(track_gener_name.lower())
