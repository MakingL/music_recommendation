import os

from flask import Flask, request, json, Response, abort

from keras import backend as K

import dl_recommend.recommand as recommend_dl
from collaborative_filtering import cf_recommendation
from download_juno.download_juno import JunoDownload

app = Flask(__name__)


@app.route('/')
def hello_world():
    return abort(404)


@app.route('/cf_recommend', methods=["POST", "GET"])
def get_cf_recommendation():
    """ 获取 CF 推荐结果 """
    json_data = request.get_json()
    message = json_data.get("msg")

    if message is None:
        # 参数不对
        return "No cf_data"
    elif message != "cf_recommend":
        return "No cf_data"

    raw_uid = json_data.get("raw_uid")

    recommend_result = cf_recommendation.collaborative_fitlering(raw_uid)

    return Response(json.dumps(recommend_result), mimetype='application/json')


@app.route('/dl_recommend', methods=["POST"])
def get_dl_recommendation():
    """ 获取 DL 推荐结果 """
    json_data = request.get_json()
    message = json_data.get("msg")

    if message is None:
        # 参数不对
        return "No dl_data"
    elif message != "dl_recommend":
        return "No dl_data"

    track_path = json_data.get("track_path")

    if not os.path.exists(track_path):
        raise RuntimeError("Track path not exists: {}".format(track_path))

    try:
        recommend_object = recommend_dl.Recommendation(track_path)
        track_genre, recommend_result = recommend_object.recommend_similar_track()
    except Exception as e:
        # Keras 目前不支持 Flask 多进程，若产生冲突，取消之前的任务
        K.clear_session()
        recommend_result = "500"
        return recommend_result

    return Response(json.dumps(recommend_result), mimetype='application/json')


@app.route('/download_juno', methods=["POST"])
def request_download_juno():
    json_data = request.get_json()
    message = json_data.get("msg")

    if message is None:
        # 参数不对
        return "No msg_data"
    elif message != "download_juno":
        return "No msg_data"

    track_url = json_data.get("track_url")

    juno_download = JunoDownload()
    data = juno_download.download_track(track_url)

    if data is None:
        return "500"
    return data


if __name__ == '__main__':
    app.run(host="0.0.0.0",
            debug=False,
            port=6016)
