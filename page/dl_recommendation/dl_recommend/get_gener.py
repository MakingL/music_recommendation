# -*- coding: utf-8 -*-
# @Time    : 2018/5/16 10:24
# @File    : get_gener.py
import os
import eyed3
from subprocess import run, PIPE
from PIL import Image
import glob
from keras.models import load_model
from keras.preprocessing.image import img_to_array, load_img
import numpy as np


class GetGener(object):
    """docstring for GetGener
        给定音频文件
        预测其gener
        生成其频谱图
        将频谱图分割
    """
    def __init__(self, path_track):
        super(GetGener, self).__init__()
        self.path_track = path_track
        song_file_name = path_track.split('/')[-1]
        song_file_name = song_file_name.split('.')[0]
        mono_track_name = "{}.mp3".format(song_file_name)
        spectrum_data_name = "{}.png".format(song_file_name)
        self.song_id = song_file_name
        self.image_size = 256
        self.num_classes = 9
        self.DEFAULT_IMG_SIZE = 256
        # 所有数据存放的目录
        DIR_DATA_RUNING = "./data"
        # 待预测的歌曲信息存放目录
        DIR_DATA_PREDICTION = '{}/prediction_track'.format(DIR_DATA_RUNING)
        DIR_NOMO_DATA = "{}/nomo".format(DIR_DATA_PREDICTION)
        DIR_SPECTRUM_DATA = "{}/spectrum".format(DIR_DATA_PREDICTION)
        DIR_PREDICTION_SLICE_SPECT = '{}/sliced_spect'.format(DIR_DATA_PREDICTION)
        for dir_file in [DIR_DATA_RUNING, DIR_NOMO_DATA, DIR_SPECTRUM_DATA, DIR_PREDICTION_SLICE_SPECT]:
            if not os.path.exists(dir_file):
                os.makedirs(dir_file)
        FILE_SPECT_PREDICTION = '{}/{}'.format(DIR_SPECTRUM_DATA, spectrum_data_name)
        FILE_MONO_PREDICTION = '{}/{}'.format(DIR_NOMO_DATA, mono_track_name)
        DIR_MODLE_FILE = 'model_files'
        MODEL_CLASSFY_GENER = '{}/{}/music_genre_classifier.hdf5'.format(DIR_DATA_RUNING, DIR_MODLE_FILE)

        if not os.path.exists(MODEL_CLASSFY_GENER):
            print("The file:{} is not existing!".format(MODEL_CLASSFY_GENER))
            raise RuntimeError("The classfy gener model is not existing!")
        self.FILE_MONO_PREDICTION = FILE_MONO_PREDICTION
        self.FILE_SPECT_PREDICTION = FILE_SPECT_PREDICTION
        self.DIR_PREDICTION_SLICE_SPECT = DIR_PREDICTION_SLICE_SPECT
        self.MODEL_CLASSFY_GENER = MODEL_CLASSFY_GENER

    def analyse_track(self):
        # helper function to delete files no longer needed
        def delete_file(file_path):
            os.remove(file_path)

        # creates a mono version of the file
        # deletes original stero mp3 and renames the temp
        # mono file to the original stero filename
        # 立体mp3文件转换成单声道文件
        def set_to_mono(input_file, output_file):
            command = "sox {} {} remix 1,2".format(input_file, output_file)
            run(command, shell=True, stdin=PIPE, stdout=PIPE)
            # delete_file(input_file)

        # converts the audio to spectrogram
        def audio_to_spect(input_file, output_file):
            command = "sox {} -n spectrogram -Y 300 -X 50 -m -r -o {}".format(input_file, output_file)
            run(command, shell=True, stdin=PIPE, stdout=PIPE)
            delete_file(input_file)

        # helper function - gets dimensions of the spectrogram
        def get_spect_dims(input_img):
            img_width, img_height = input_img.size
            return img_width, img_height

        # helper function - calculates the number of slices available from the full size spectrogram
        def get_num_slices(img_width):
            n_slices = img_width // self.DEFAULT_IMG_SIZE
            return n_slices

        # helper function - returns a list of coordinates/dimensions where to split the spectrogram
        def get_slice_dims(input_img):
            img_width, img_height = get_spect_dims(input_img)
            num_slices = get_num_slices(img_width)
            unused_size = img_width - (num_slices * self.DEFAULT_IMG_SIZE)
            start_px = 0 + unused_size
            image_dims = []
            for i in range(num_slices):
                img_width = self.DEFAULT_IMG_SIZE
                image_dims.append((start_px, start_px + self.DEFAULT_IMG_SIZE))
                start_px += self.DEFAULT_IMG_SIZE
            return image_dims

        # slices the spectrogram into individual sample images
        def slice_spect(input_file, save_dir):
            img = Image.open(input_file)
            spect_name = input_file.split('/')[-1]
            input_file_cleaned = spect_name.replace('.png','')
            dims = get_slice_dims(img)
            counter = 0
            for dim in dims:
                counter_formatted = str(counter).zfill(3)
                img_name = '{}__{}.png'.format(input_file_cleaned, counter_formatted)
                start_width = dim[0]
                end_width = dim[1]
                sliced_img = img.crop((start_width, 0, end_width, self.DEFAULT_IMG_SIZE))
                output_file = "{}/{}".format(save_dir, img_name)
                sliced_img.save(output_file)
                counter += 1
            delete_file(input_file)

        # 判断是否为单声道文件
        def is_mono(filename):
            audiofile = eyed3.load(filename)
            return audiofile.info.mode == 'Mono'

        # 导入模型
        model = load_model(self.MODEL_CLASSFY_GENER)

        # Create and save spectrograms
        if is_mono(self.path_track) == False:
            # 判断是否为单声道文件
            set_to_mono(self.path_track, self.FILE_MONO_PREDICTION)
        else:
            self.FILE_MONO_PREDICTION = self.path_track

        # 转换为频谱图
        audio_to_spect(self.FILE_MONO_PREDICTION, self.FILE_SPECT_PREDICTION)
        # 频谱分割
        slice_spect(self.FILE_SPECT_PREDICTION, self.DIR_PREDICTION_SLICE_SPECT)

        # Predict against saved spectrograms
        # 预测保存的频谱
        spect_files = glob.glob('{}/{}*.png'.format(self.DIR_PREDICTION_SLICE_SPECT, self.song_id))

        images = []
        for file in spect_files:
            img = load_img('{}'.format(file), target_size=(self.image_size, self.image_size))
            img_array = img_to_array(img)
            img_array = np.expand_dims(img_array, axis=0)
            img_array /= (self.image_size-1)
            images.append(img_array)

        # print('Analyzing file...')

        predictions = []
        for image in images:
            prediction = model.predict(image)
            predictions.append(prediction)

        # Sum individual probabilities for all spectrograms
        # 计算置信度
        pred_sum = sum(a[0] for a in predictions)
        biggest_num = np.amax(pred_sum)
        pct_confidence = round((biggest_num / sum(pred_sum) * 100), 2)
        pred_class_num = np.argmax(pred_sum)

        # 预测出的 gener 映射
        class_labels = {
            0:'Breakbeat',
            1:'Dancehall/Ragga',
            2:'Downtempo',
            3:'Drum and Bass',
            4:'Funky House',
            5:'Hip Hop/R&B',
            6:'Minimal House',
            7:'Rock/Indie',
            8:'Trance'
        }

        return class_labels[pred_class_num], pct_confidence




