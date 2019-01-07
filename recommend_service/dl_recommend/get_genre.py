# -*- coding: utf-8 -*-
# @Time    : 2018/5/16 10:24
# @File    : get_genre.py
import glob
import os
from subprocess import run, PIPE

import eyed3
import numpy as np
from PIL import Image
from keras.models import load_model
from keras.preprocessing.image import img_to_array, load_img


class GetGenre(object):
    """docstring for GetGenre
        给定音频文件
        预测其genre
        生成其频谱图
        将频谱图分割
    """

    def __init__(self, path_track):
        super(GetGenre, self).__init__()
        self.path_track = path_track
        song_file_name = os.path.basename(path_track)
        song_file_name = song_file_name.split('.')[0]
        mono_track_name = "{}.mp3".format(song_file_name)
        spectrum_data_name = "{}.png".format(song_file_name)
        self.song_id = song_file_name
        self.image_size = 256
        self.num_classes = 9
        self.DEFAULT_IMG_SIZE = 256

        # 所有数据存放的目录
        dir_data = os.path.join(os.path.dirname(__file__), "dl_data")
        # 待预测的歌曲信息存放目录
        dir_data_prediction = os.path.join(dir_data, "prediction_track")
        dir_nomo_data = os.path.join(dir_data_prediction, "nomo")
        dir_spectrum_data = os.path.join(dir_data_prediction, "spectrum")
        dir_prediction_slice_spect = os.path.join(dir_data_prediction, "sliced_spect")
        for dir_file in [dir_data, dir_nomo_data, dir_spectrum_data, dir_prediction_slice_spect]:
            if not os.path.exists(dir_file):
                os.makedirs(dir_file)

        dir_spectrum_prediction = os.path.join(dir_spectrum_data, spectrum_data_name)
        file_mono_prediction = os.path.join(dir_nomo_data, mono_track_name)
        model_classify_genre = os.path.join(dir_data, "model_files", "music_genre_classifier.hdf5")

        if not os.path.exists(model_classify_genre):
            print("The file: {} is not existing!".format(model_classify_genre))
            raise RuntimeError("The classify genre model is not existing!")
        self.file_mono_prediction = file_mono_prediction
        self.dir_spectrum_prediction = dir_spectrum_prediction
        self.model_classify_genre = model_classify_genre
        self.dir_prediction_slice_spect = dir_prediction_slice_spect

    def analyse_track(self):
        # helper function to delete files no longer needed
        def delete_file(file_path):
            os.remove(file_path)

        # creates a mono version of the file
        # deletes original mp3 and renames the temp
        # mono file to the original filename
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
        def get_spectrogram_dims(input_img):
            img_width, img_height = input_img.size
            return img_width, img_height

        # helper function - calculates the number of slices available from the full size spectrogram
        def get_num_slices(img_width):
            n_slices = img_width // self.DEFAULT_IMG_SIZE
            return n_slices

        # helper function - returns a list of coordinates/dimensions where to split the spectrogram
        def get_slice_dims(input_img):
            img_width, img_height = get_spectrogram_dims(input_img)
            num_slices = get_num_slices(img_width)
            unused_size = img_width - (num_slices * self.DEFAULT_IMG_SIZE)
            start_px = 0 + unused_size
            image_dims = []
            for i in range(num_slices):
                image_dims.append((start_px, start_px + self.DEFAULT_IMG_SIZE))
                start_px += self.DEFAULT_IMG_SIZE
            return image_dims

        # slices the spectrogram into individual sample images
        def slice_spectrogram(input_file, save_dir):
            image_file = Image.open(input_file)
            input_file_cleaned = os.path.basename(input_file.replace('.png', ''))
            dims = get_slice_dims(image_file)
            counter = 0
            for dim in dims:
                counter_formatted = str(counter).zfill(3)
                img_name = '{}__{}.png'.format(input_file_cleaned, counter_formatted)
                start_width = dim[0]
                end_width = dim[1]
                sliced_img = image_file.crop((start_width, 0, end_width, self.DEFAULT_IMG_SIZE))
                # output_file = "{}/{}".format(save_dir, img_name)
                output_file = os.path.join(save_dir, img_name)
                sliced_img.save(output_file)
                counter += 1
            delete_file(input_file)

        # 判断是否为单声道文件
        def is_mono(filename):
            audio_file = eyed3.load(filename)
            return audio_file.info.mode == 'Mono'

        # 导入模型
        model = load_model(self.model_classify_genre)

        # Create and save spectrogram
        if not is_mono(self.path_track):
            # 判断是否为单声道文件
            set_to_mono(self.path_track, self.file_mono_prediction)
        else:
            self.file_mono_prediction = self.path_track

        # 转换为频谱图
        audio_to_spect(self.file_mono_prediction, self.dir_spectrum_prediction)
        # 频谱分割
        slice_spectrogram(self.dir_spectrum_prediction, self.dir_prediction_slice_spect)

        # Predict against saved spectrogram
        # 预测保存的频谱
        spectrum_files = glob.glob(os.path.join(self.dir_prediction_slice_spect, '{}*.png'.format(self.song_id)))

        images = []
        for file in spectrum_files:
            img = load_img(file, target_size=(self.image_size, self.image_size))
            img_array = img_to_array(img)
            img_array = np.expand_dims(img_array, axis=0)
            img_array /= (self.image_size - 1)
            images.append(img_array)

        # print('Analyzing file...')

        predictions = []
        for image in images:
            prediction = model.predict(image)
            predictions.append(prediction)

        # Sum individual probabilities for all spectrogram
        # 计算置信度
        predicted_sum = sum(a[0] for a in predictions)
        biggest_num = np.amax(predicted_sum)
        pct_confidence = round((biggest_num / sum(predicted_sum) * 100), 2)
        predicted_class_num = np.argmax(predicted_sum)

        # 预测出的 genre 映射
        class_labels = {
            0: 'Breakbeat',
            1: 'Dancehall/Ragga',
            2: 'Downtempo',
            3: 'Drum and Bass',
            4: 'Funky House',
            5: 'Hip Hop/R&B',
            6: 'Minimal House',
            7: 'Rock/Indie',
            8: 'Trance'
        }

        return class_labels[predicted_class_num], pct_confidence
