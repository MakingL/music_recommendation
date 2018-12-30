# -*- coding: utf-8 -*-
# @Time    : 2018/4/27 11:07
# @File    : id_convert_neteastid.py
import requests
import json

file_infor = "echonest_track_name.txt"
with open(file_infor, encoding='utf8') as i_f:
    for index, line in enumerate(i_f):
        data = line.strip('\n').split('<SEP>')
        track_name = data[2] + "  " + data[1]
        print(track_name)
        data = {'track_name': track_name, "limit": 1}
        r = requests.post("http://localhost/music_recommender/page/search_song_f_name.php", data=data)
        # print(r.text)
        result = json.loads(r.text)
        # print(result)
        # print(result['code'])
        if result['code'] == 200:
            # song_data = result['0']
            song_id = result['0']['song_id']
            print(song_id)

        if index > 5:
            break
