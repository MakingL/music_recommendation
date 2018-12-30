# coding: utf-8
import sys
import requests
import os
import re
import time
import string
import time
# from multiprocessing import Queue
# import threading

# 歌曲保存地址
# DIR_MUSIC = '../../juno_track_data'
# for dir_file in [DIR_MUSIC, ]:
#     if not os.path.exists(dir_file):
#         os.makedirs(dir_file)


# ============ 爬虫代理部分=======================
def get_proxy():
    """ 请求一个代理IP"""
    return requests.get("http://127.0.0.1:6010/get/").content

def delete_proxy(proxy):
    requests.get("http://127.0.0.1:6010/delete/?proxy={}".format(proxy))

def request_music_web(track_url):
    user_agent = '"Mozilla/5.0 ' \
                 '(Windows NT 6.2; WOW64) ' \
                 'AppleWebKit/537.36 ' \
                 '(KHTML, like Gecko) ' \
                 'Chrome/38.0.2125.122 ' \
                 'Safari/537.36"'
    headers = {'User-Agent': user_agent}
    retry_count = 5
    proxy = get_proxy().decode()
    while retry_count > 0:
        try:
            html = requests.get(track_url,
                                headers=headers,
                                proxies={
                                "http": "http://{}".format(proxy),
                                },
                                timeout=60)
            # if str(html.status_code) == '503':
            #     delete_proxy(proxy)
            #     raise RuntimeError
            # 使用代理访问
            return html
        except Exception as e:
            retry_count -= 1
    # 出错5次, 删除代理池中代理
    delete_proxy(proxy)
    return None

def download_track(track_url):
    retry_time = 1
    while retry_time > 0:
        r = request_music_web(track_url)
        if r is not None:
            return r
        retry_time -= 1
    return None

# =============================================

#  下载并保存音乐文件
def download_save_track(track_url, track_save_path):
    try:
        response = download_track(track_url)
        if response is None:
            return False
        if str(response.status_code) != '200':
            # print('%s responsed error code %s' % (track_url, response.status_code))
            # threadLock.acquire()
            # f_logs.writelines('%s responsed error code %s\n' % (track_file_name, response.status_code))
            # threadLock.release()
            return False
        # dir_track_save = "{}".format(DIR_MUSIC)
        # track_full_name = "{}/{}".format(dir_track_save, track_file_name)
        with open(track_save_path, "wb+") as track_file:
            track_file.write(response.content)
    except Exception as e:
        # print(str(e))
        # print("download error!")
        # threadLock.acquire()
        # f_logs.writelines('%s download error\n' % (track_file_name))
        # threadLock.release()
        return False
    else:
        return True

# helper function to delete files no longer needed
def delete_file(file_path):
    os.remove(file_path)


track_url = sys.argv[1]
track_save_path = sys.argv[2]

if download_save_track(track_url, track_save_path) is True:
    print("200")
else:
    print("504")
