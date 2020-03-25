import pymongo as pm
import main as m
import chatterbot as cb
import os
from chatterbot.trainers import ChatterBotCorpusTrainer
from chatterbot.trainers import ListTrainer
from datetime import datetime


#Initializing everything
client = pm.MongoClient('localhost', 27017)
db = client.escabot
userchat  = db.userchat
userdata = db.userdata
#bot = cb.ChatBot('ESCABOT')
bot = cb.ChatBot(
    'ESCABOT',
    storage_adapter='chatterbot.storage.MongoDatabaseAdapter',
    logic_adapters=[
        'chatterbot.logic.BestMatch'
    ],
    database_uri='mongodb://localhost:27017/escabot'
)
#trainer = ListTrainer(bot)
#storage_adapter="chatterbot.storage.MongoDatabaseAdapter"


if __name__ == "__main__":
    flag = True
    print("NOW RUNNNING")
    while True:
        cursor = userchat.find({"type":"sent", "replied":False})
        for document in cursor:
            mid = document['mid']
            message = document['message'].lower()
            sender = document['sender']
            if flag:
                reply = str(bot.get_response(message))
                if message=='second layer':
                    flag = False
                    reply = "Second Layer Activated"
            else:
                if message == 'first layer':
                    flag = True
                    reply = "First Layer Activated"
                    continue
                top_n = 3
                sentence = m.inference(seed, top_n)
                reply = ''.join(sentence)
            now = datetime.now() # current date and time
            date = now.strftime("%d-%m-%Y")
            time = now.strftime("%H:%M:%S")
            print(document,)
            print("REPLY")
            print(reply)
            print("\n\n\n")
            userchat.insert({"type":"recieved", "mid":mid, "sender":sender, "message":reply, "time":time, "date":date})
            userchat.update_one({"mid":mid, "sender":sender},{"$set":{"replied":True}})
