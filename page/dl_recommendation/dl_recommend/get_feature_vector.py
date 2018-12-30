# -*- coding: utf-8 -*-
# @Time    : 2018/5/16 10:27
# @File    : get_feature_vector.py
from keras import backend as K
from keras.models import load_model
from keras.models import Model
from keras.preprocessing.image import array_to_img, img_to_array, load_img
import os
import glob
import numpy as np
import pickle

class GetFeatureVector(object):
    """docstring for GetFeatureVector
        从流派预测的结果中得到歌曲频谱图
        切割频谱图
        计算各频谱图的特征向量
        计算所有图片的平均特征向量
    """
    def __init__(self, song_id):
        super(GetFeatureVector, self).__init__()

        image_size = 256

        if K.image_data_format() == 'channels_first':
            input_shape = (3, image_size, image_size)
        else:
            input_shape = (image_size, image_size, 3)

        self.song_id = song_id
        # 所有数据存放的目录
        DIR_DATA_RUNING = "./data"
        # 模型文件存放目录
        DIR_MODLE_FILE = 'model_files'
        MODEL_CLASSFY_GENER = '{}/{}/music_feature_extractor.hdf5'.format(DIR_DATA_RUNING, DIR_MODLE_FILE)
        if not os.path.exists(MODEL_CLASSFY_GENER):
            print("The file:{} is not existing!".format(MODEL_CLASSFY_GENER))
            raise RuntimeError("The classfy gener model is not existing!")

        # 待生成平均特征向量的频谱图的路径
        DIR_PREDICTION_SLICE_SPECT = './data/prediction_track/sliced_spect'
        if not os.path.exists(DIR_PREDICTION_SLICE_SPECT):
            print("频谱图数据路径不存在! file:{}".format(DIR_PREDICTION_SLICE_SPECT))
            raise RuntimeError("路径不存在")

        # 待推荐的歌曲的歌曲ID
        SONG_ID_RECOMMENDATION = song_id
        DIR_FEATURE_VECTOR_ARRAY = '{}/feature_arrays_recommendation'.format(DIR_DATA_RUNING)
        DIR_AVERAGE_FEATURE_VECTOR   = '{}/average_feature_arrays'.format(DIR_DATA_RUNING)
        for dir_file in [DIR_FEATURE_VECTOR_ARRAY, DIR_AVERAGE_FEATURE_VECTOR]:
            if not os.path.exists(dir_file):
                os.makedirs(dir_file)

        self.MODEL_CLASSFY_GENER = MODEL_CLASSFY_GENER
        self.DIR_PREDICTION_SLICE_SPECT = DIR_PREDICTION_SLICE_SPECT
        self.DIR_FEATURE_VECTOR_ARRAY = DIR_FEATURE_VECTOR_ARRAY
        self.SONG_ID_RECOMMENDATION = SONG_ID_RECOMMENDATION
        self.DIR_AVERAGE_FEATURE_VECTOR = DIR_AVERAGE_FEATURE_VECTOR

    def get_feature(self):
        # helper function to delete files no longer needed
        def delete_file(file_path):
            os.remove(file_path)

        # 导入特征提取模型
        feature_vec_model = load_model(self.MODEL_CLASSFY_GENER)

        # Predict against saved spectrograms
        # 返回值是完整的路径
        spect_files = glob.glob('{}/{}*.png'.format(self.DIR_PREDICTION_SLICE_SPECT, self.song_id))
        # 为所有频谱生成特征向量矩阵 并保存在相应的目录下
        for file in spect_files:
            img = load_img('{}'.format(file), target_size=(256, 256))
            img_array = img_to_array(img)
            img_array = np.expand_dims(img_array, axis=0)
            img_array /= 255

            # 得到其特征向量
            feature_vec = feature_vec_model.predict(img_array)

            # 解析出文件名
            file_info = file.replace('.png','.npy').split('/')[-1]

            out_file_name = '{}'.format(file_info)
            out_file_path = '{}/{}'.format(self.DIR_FEATURE_VECTOR_ARRAY, out_file_name)

            # 保存特征向量到指定路径
            np.save(out_file_path, feature_vec)
            # 删除频谱图
            delete_file(file)

        # 保证 待求平均特征向量的目录下只有一首歌的 特征向量
        vector_files = glob.glob('{}/{}*.npy'.format(self.DIR_FEATURE_VECTOR_ARRAY, self.song_id))
        feature_vector_files = list()
        files = []
        for file_feature_vector in vector_files:
            feature_vector_files.append(file_feature_vector)
            npy_file = np.load(file_feature_vector)
            files.append(npy_file)
        song_avg_vec = np.average(files, axis=0)

        for file_feature_vector in feature_vector_files:
            # 删除生成的特征向量文件
            delete_file(file_feature_vector)

        # 保存
        save_name = '{}/{}.npy'.format(self.DIR_FEATURE_VECTOR_ARRAY, self.SONG_ID_RECOMMENDATION)
        np.save(save_name, song_avg_vec)
        # print("Get average vector have done!")
