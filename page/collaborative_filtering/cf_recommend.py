# -*- coding: utf-8 -*-
# @Time    : 2018/5/20 11:33
# @File    : cf_recommend.py
import sys
import json
from collaborative_filtering import cf_recommendation

raw_uid = sys.argv[1]
#raw_uid = '7147fe199c61be0625a5323e24617375'


recommend_result = cf_recommendation.collaborative_fitlering(raw_uid)

result = json.dumps(recommend_result)
print(result)
