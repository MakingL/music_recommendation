# -*- coding: utf-8 -*-
# @Time    : 2018/4/27 14:49
# @File    : get_song_id.py
import json

import requests
import time

file_track_infor = "echonest_track_name.txt"
massg_file = "massge_file_count.txt"
line_count = 0

song_information = dict()
with open(file_track_infor, encoding='utf8') as i_f:
    for index, line in enumerate(i_f):
        data = line.strip('\n').split('<SEP>')
        song_information[data[0]] = (data[1], data[2])
        line_count += 1
print("total lines: %d" % line_count)

song_id_set = set()
file_user_track = "trainSet_10k_5.txt"
with open(file_user_track, encoding='utf8') as data_f:
    for index_data, data_line in enumerate(data_f):
        data = data_line.strip('\n').split('\t')
        # print(data[1])
        song_id_set.add(data[1])
print("song id total size: %d" % len(song_id_set))

file_track_map = "track_id_mapping.txt"
log_file = "logs_running.txt"
error_count = 0
success_count = 0

with open(file_track_map, 'w+') as f_id_mapping:
    with open(log_file, 'w+', encoding="utf8") as f_logs:
        number_count = 0
        for index_data, raw_song_id in enumerate(song_id_set):
            # if index_data > 201:
            #     break
            if index_data % 200 == 0:
                print("%d data has been done. sleep 1s.. %s" % (index_data, time.ctime()))
                time.sleep(1)

            # print('continue %s' % time.ctime())
            song_artist, song_name = song_information[raw_song_id]
            track_name = song_name + "  " + song_artist
            data = {'track_name': track_name, "limit": 1}
            try:
                # r = requests.post("http://localhost/music_recommender/page/search_song_f_name.php", data=data)
                r = requests.post("http://music.makepace.top/page/search_song_f_name.php", data=data)
                result = json.loads(r.text)
                if result['code'] == 200:
                    song_data = result['0']
                    neteast_song_id = song_data['song_id']
                    f_id_mapping.writelines('%s\t%s\n' % (raw_song_id, neteast_song_id))
                    success_count += 1
                else:
                    error_count += 1
                    f_logs.writelines('%s\t%s\t%s\t\t%s\n' % (raw_song_id, song_name, song_artist, result['code']))
            except Exception as e:
                error_count += 1
                print(e)
                # print(r.text)
                f_logs.writelines('%s\t%s\t%s\n' % (raw_song_id, song_name, song_artist))
                # raise e

with open(massg_file, 'w+', encoding="utf8") as f_massg:
    f_massg.writelines("raw song id total size: %d\n" % len(song_id_set))
    f_massg.writelines("success id convert total size: %d\n" % success_count)
    f_massg.writelines("error id convert total size: %d\n" % error_count)

print("error count: %d" % error_count)
print("success count: %d" % success_count)
print("Every thing has been done!")
