# -*- coding: utf-8 -*-
# @Time    : 2018/5/16 12:44
# @File    : recommend_similar_track.py
import os
import json
import requests
import dl_recommend.recommand as recommendMain
from pprint import pprint
import sys

# def request_music(track_url, track_file_name):
#     user_agent = '"Mozilla/5.0 ' \
#                  '(Windows NT 6.2; WOW64) ' \
#                  'AppleWebKit/537.36 ' \
#                  '(KHTML, like Gecko) ' \
#                  'Chrome/38.0.2125.122 ' \
#                  'Safari/537.36"'
#     headers = {'User-Agent': user_agent}

#     response = requests.get(track_url,
#                         headers=headers)
#     print(response.status_code)
#     if str(response.status_code) != '200':
#         return False
#     with open(track_file_name, "wb+") as track_file:
#         track_file.write(response.content)

#     return True

# track_url = sys.argv[1]
# track_path = "./data/musicfile.mp3"
# if request_music(track_url, track_path) is False:
#     sys.exit("Cannot get track file!")

track_path = sys.argv[1]
if not os.path.exists(track_path):
    raise RuntimeError("Track path not exists: {}".format(track_path))

recommend_object = recommendMain.Recommandation(track_path)
track_gener, recommend_result = recommend_object.recommend_similar_track()
print(track_gener)
json_result = json.dumps(recommend_result)
print(json_result)


