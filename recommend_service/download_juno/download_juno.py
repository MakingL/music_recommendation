# -*- coding: utf-8 -*-
# @Time    : 2019/1/7 13:43
# @Author  : MLee
# @File    : download_juno.py

# ============ 爬虫代理部分=======================
import requests


class JunoDownload(object):
    """docstring for JunoDownload"""

    def __init__(self):
        super(JunoDownload, self).__init__()

    def get_proxy(self):
        """ 请求一个代理IP"""
        return requests.get("http://123.207.35.36:5010/get/").content

    def delete_proxy(self, proxy):
        requests.get("http://123.207.35.36:5010/delete/?proxy={}".format(proxy))

    def request_music_web(self, track_url):
        user_agent = '"Mozilla/5.0 ' \
                     '(Windows NT 6.2; WOW64) ' \
                     'AppleWebKit/537.36 ' \
                     '(KHTML, like Gecko) ' \
                     'Chrome/38.0.2125.122 ' \
                     'Safari/537.36"'
        headers = {'User-Agent': user_agent}
        retry_count = 5
        proxy = self.get_proxy().decode()
        while retry_count > 0:
            try:
                html = requests.get(track_url,
                                    headers=headers,
                                    proxies={
                                        "http": "http://{}".format(proxy),
                                    },
                                    timeout=60)
                # html.raise_for_status()
                # 使用代理访问
                return html
            except Exception as e:
                retry_count -= 1
        # 出错5次, 删除代理池中代理
        self.delete_proxy(proxy)
        return None

    def download_track(self, track_url):
        retry_time = 1
        while retry_time > 0:
            r = self.request_music_web(track_url)
            if r is not None:
                return r.content
            retry_time -= 1
        return None

    # #  下载并保存音乐文件
    # def download_save_track(track_url, track_save_path):
    #     try:
    #         response = download_track(track_url)
    #         if response is None:
    #             return False
    #         if str(response.status_code) != '200':
    #             return False
    #         with open(track_save_path, "wb+") as track_file:
    #             track_file.write(response.content)
    #     except Exception as e:
    #         return False
    #     else:
    #         return True


if __name__ == '__main__':
    track_url = "https://www.junodownload.com/MP3/SF3028408-02-01-02.mp3"
    juno_download = JunoDownload()
    print(juno_download.download_track(track_url))
