# -*- coding: utf-8 -*-
# @Time    : 2018/5/16 10:27
# @File    : get_feature_vector.py
import glob
import os

import numpy as np
from keras import backend
from keras.models import load_model
from keras.preprocessing.image import img_to_array, load_img


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

        if backend.image_data_format() == 'channels_first':
            input_shape = (3, image_size, image_size)
        else:
            input_shape = (image_size, image_size, 3)

        self.song_id = song_id
        # 所有数据存放的目录
        dir_data = os.path.join(os.path.dirname(__file__), "dl_data")
        # 模型文件存放目录
        model_classify_genre = os.path.join(dir_data, "model_files", "music_feature_extractor.hdf5")
        if not os.path.exists(model_classify_genre):
            print("The file:{} is not existing!".format(model_classify_genre))
            raise RuntimeError("The classify genre model is not existing!")

        # 待生成平均特征向量的频谱图的路径
        dir_prediction_slice_spectrum = os.path.join(dir_data, "prediction_track", "sliced_spect")
        if not os.path.exists(dir_prediction_slice_spectrum):
            print("频谱图数据路径不存在! file:{}".format(dir_prediction_slice_spectrum))
            raise RuntimeError("路径不存在")

        dir_feature_vector_array = os.path.join(dir_data, "feature_arrays_recommendation")
        dir_average_feature_vector = os.path.join(dir_data, "average_feature_arrays")
        for dir_file in [dir_feature_vector_array, dir_average_feature_vector]:
            if not os.path.exists(dir_file):
                os.makedirs(dir_file)

        self.MODEL_CLASSFY_genre = model_classify_genre
        self.DIR_PREDICTION_SLICE_SPECT = dir_prediction_slice_spectrum
        self.DIR_FEATURE_VECTOR_ARRAY = dir_feature_vector_array
        self.SONG_ID_RECOMMENDATION = song_id
        self.DIR_AVERAGE_FEATURE_VECTOR = dir_average_feature_vector

    def get_feature(self):
        def delete_file(file_path):
            # helper function to delete files no longer needed
            os.remove(file_path)

        # 导入特征提取模型
        feature_vec_model = load_model(self.MODEL_CLASSFY_genre)

        # Predict against saved spectrogram
        # 返回值是完整的路径
        spectrum_files = glob.glob(os.path.join(self.DIR_PREDICTION_SLICE_SPECT, '{}*.png'.format(self.song_id)))
        spectrum_files = [os.path.join(self.DIR_PREDICTION_SLICE_SPECT, os.path.basename(f)) for f in spectrum_files]

        # 为所有频谱生成特征向量矩阵 并保存在相应的目录下
        for file in spectrum_files:
            img = load_img(file, target_size=(256, 256))
            img_array = img_to_array(img)
            img_array = np.expand_dims(img_array, axis=0)
            img_array /= 255

            # 得到其特征向量
            feature_vec = feature_vec_model.predict(img_array)

            # 解析出文件名
            out_file_name = os.path.basename(file.replace('.png', '.npy'))
            out_file_path = os.path.join(self.DIR_FEATURE_VECTOR_ARRAY, out_file_name)

            # 保存特征向量到指定路径
            np.save(out_file_path, feature_vec)
            # 删除频谱图
            delete_file(file)

        # 求向量的平均值
        vector_files = glob.glob(os.path.join(self.DIR_FEATURE_VECTOR_ARRAY, '{}*.npy'.format(self.song_id)))
        feature_vector_files = list()
        files = list()
        for file_feature_vector in vector_files:
            feature_vector_files.append(file_feature_vector)
            npy_file = np.load(file_feature_vector)
            files.append(npy_file)
        song_avg_vec = np.average(files, axis=0)

        for file_feature_vector in feature_vector_files:
            # 删除生成的特征向量文件
            delete_file(file_feature_vector)

        # 保存
        save_name = os.path.join(self.DIR_FEATURE_VECTOR_ARRAY, '{}.npy'.format(self.SONG_ID_RECOMMENDATION))
        np.save(save_name, song_avg_vec)

