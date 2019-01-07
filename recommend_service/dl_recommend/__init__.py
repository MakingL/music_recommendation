# -*- coding: utf-8 -*-
# @Time    : 2018/5/16 10:13
# @File    : __init__.py.py

# 此模块功能
# 给定任意歌曲的音频文件
# 预测其 genre, 得到其频谱图
# 将其频谱图转换为特征向量，并求出其平均特征向量
# 导入已有数据集的平均特征向量矩阵
# 求待推荐音频与已知数据集中音频的相似度
# 取相似度最高的作为推荐

# 所有数据保存在 目录 cf_data 下
# model_files 模型文件目录
#   final_data.pkl 歌曲信息文件
# average_feature_arrays
#   数据集的平均特征向量存放目录
# prediction_track 待预测歌曲产生的信息的目录
