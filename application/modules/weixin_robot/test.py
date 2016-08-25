#!/usr/bin/env python
# coding: utf-8
import ConfigParser
import re
import sys
import shutil
import logging
import stat

from wxbot import *


class MyWXBot(WXBot):
    config = {}
    reload(sys)
    sys.setdefaultencoding('utf-8')

    def handle_msg_all(self, msg):
        try :
            configPath = os.path.dirname(os.path.realpath(__file__)) + "/robots/" + str(self.uin) + "/replyConfig.conf"
            if not os.path.exists(configPath):
                shutil.copy(os.path.dirname(os.path.realpath(__file__)) + "/replyConfig.conf",configPath)
                os.chmod(configPath,stat.S_IRWXU + stat.S_IWOTH + stat.S_IRWXG)
            f = file(configPath)
            self.config = json.load(f)
            logger.debug(self.config)
        except Exception,e:
            logger.exception('exception')
        if msg['content']['type'] == 0:
            self.send_message_by_rules(msg,'text',msg['user']['id'])
        elif msg['msg_type_id'] == 99 and msg['content']['type'] == 5:
            #处理加好友请求
            if self.config["auto_add"] == 1:
                logger.debug ("好友请求:"  + msg['content']['data']['nickname'])
                user = msg['content']['data']['userName']
                ticket = msg['content']['data']['ticket']
                if self.verify_user(user,ticket) :
                    logger.debug ("自动通过好友请求")
                    self.send_message_by_rules(msg,'add',user)
                else :
                    logger.debug("未能通过请求")
        else :
            pass

    def send_message_by_rules(self,msg,msg_type,touser):
        for i in range(len(self.config['reply_rules'])):
            config_item = self.config['reply_rules'][i]
            if config_item["msg_type"] == msg_type and (config_item['keywords'] == '' or re.search(config_item['keywords'], msg['content']['data']) != None ) :
                conf_reply = config_item['reply']
                for j in range(len(conf_reply)):
                    reply_item = conf_reply[j]
                    if reply_item['type'] == 'text':
                        self.send_msg_by_uid(reply_item['content'], touser)
                    elif reply_item['type'] == 'image':
                        (result,mid) = self.send_img_msg(os.path.dirname(os.path.realpath(__file__)) + "/" + reply_item['content'], self.getmid(reply_item), touser)
                        if result and mid is not None:
                            reply_item['mid'] = mid
                            self.config['reply_rules'][i]['reply'][j] = reply_item
                    else:
                        logger.debug(reply_item['type'] + " not supported")
        with open(self.base_dir + "/robots/" + self.uin + "/replyConfig.conf","w") as f:
            f.write(json.dumps(self.config))

    def getmid(self,reply_item):
        if 'mid' in reply_item:
            return reply_item['mid']
        else:
            return None

    def schedule(self):
        with open(self.base_dir + '/account_list.json', 'r') as f:
            find = 0;
            users = json.load(f)
            if users is None:
                users = []
            for i in range(len(users)):
                user = users[i]
                if (("uin" in user and user['uin'] == self.uin) or ("uuid" in user and user['uuid'] == self.uuid )) and ("task" in user and user['task'] == 'exit'):
                    find = 1
                    if "uin" in user and self.uin:
                        users[i] = {"status": 0, "desc": "exit", "nickname": user['nickname'], "uin": self.uin}
                    elif "uuid" in user and self.uuid:
                        users[i] = {"status": 0, "desc": "exit",  "uuid": self.uuid}
                    break
        if find == 1:
            logger.info("begin to quit process");
            with open(self.base_dir + '/account_list.json','w') as f:
                f.write(json.dumps(users))
            os._exit(0)
        time.sleep(1)

def main():
    logger.info("begin to start")
    bot = MyWXBot()
    bot.DEBUG = True
    bot.conf['qr'] = 'png'
    try :
        bot.run()
    except Exception as e:
        logger.error(e.message)
        logger.exception('exception')

if __name__ == '__main__':
    main()
