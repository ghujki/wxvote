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
            logging.debug(self.config)
        except Exception,e:
            logging.exception('exception')
        if msg['content']['type'] == 0:
            self.send_message_by_rules(msg,'text',msg['user']['id'])
        elif msg['msg_type_id'] == 99 and msg['content']['type'] == 5:
            #处理加好友请求
            if self.config["auto_add"] == 1:
                logging.debug ("好友请求:"  + msg['content']['data']['nickname'])
                user = msg['content']['data']['userName']
                ticket = msg['content']['data']['ticket']
                if self.verify_user(user,ticket) :
                    logging.debug ("自动通过好友请求")
                    self.send_message_by_rules(msg,'add',user)
                else :
                    logging.debug("未能通过请求")
        else :
            pass

    def send_message_by_rules(self,msg,msg_type,touser):
        for config_item in self.config['reply_rules']:
            if config_item["msg_type"] == msg_type and (config_item['keywords'] == '' or re.search(config_item['keywords'], msg['content']['data']) != None ) :
                conf_reply = config_item['reply']
                for reply_item in conf_reply:
                    if reply_item['type'] == 'text':
                        self.send_msg_by_uid(reply_item['content'], touser)
                    elif reply_item['type'] == 'image':
                        self.send_img_msg_by_uid(os.path.dirname(os.path.realpath(__file__)) + "/" + reply_item['content'], touser)
                    else:
                        logging.debug(reply_item['type'] + " not supported")


    def schedule(self):
        logging.debug("scheduling...");
        with open(self.base_dir + '/account_list.json', 'r') as f:
            find = 0;
            users = json.load(f)
            if users is None:
                users = []
            for i in range(len(users)):
                user = users[i]
                if "uin" in user and user['uin'] == self.uin and "task" in user and user['task'] == 'exit':
                    find = 1
                    users[i] = {"status": 0, "desc": "exit", "nickname": user['nickname'], "uin": user['uin']}
                    break
        if find == 1:
            with open(self.base_dir + '/account_list.json','w') as f:
                f.write(json.dumps(users))
                sys.exit(0)
        time.sleep(3)

def main():
    #logging.debug "hello===============" + os.path.dirname(os.path.realpath(__file__))
    logging.basicConfig(filename='myapp.log', level=logging.DEBUG,format='%(asctime)s %(message)s', datefmt='%Y/%m/%d %I:%M:%S %p')
    bot = MyWXBot()
    bot.DEBUG = True
    bot.conf['qr'] = 'png'
    try :
        bot.run()
    except Exception as e:
        logging.error(e.message)
        logging.exception('exception')


if __name__ == '__main__':
    main()
