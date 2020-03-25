import tensorflow as tf
import tensorlayer as tl
from tensorlayer.models.seq2seq import Seq2seq
import pickle


decoder_seq_length = 20
num_epochs = 100
emb_dim = 1024
batch_size = 128



def inference(seed, top_n):
        model_.eval()
        seed_id = [word2idx.get(w, unk_id) for w in seed.split(" ")]
        sentence_id = model_(inputs=[[seed_id]], seq_length=20, start_token=start_id, top_n = top_n)
        sentence = []
        for w_id in sentence_id[0]:
            w = idx2word[w_id]
            if w == 'end_id':
                break
            sentence = sentence + [w]
        return sentence

with open('objs.pkl','rb') as f:
  n_step,src_vocab_size,word2idx,idx2word,unk_id,pad_id,start_id,end_id,vocabulary_size,src_len,tgt_len = pickle.load(f)

model_ = Seq2seq(
        decoder_seq_length = decoder_seq_length,
        cell_enc=tf.keras.layers.GRUCell,
        cell_dec=tf.keras.layers.GRUCell,
        n_layer=3,
        n_units=256,
        embedding_layer=tl.layers.Embedding(vocabulary_size=vocabulary_size, embedding_size=emb_dim),
        )

load_weights = tl.files.load_npz(name='model_twitter.npz')
tl.files.assign_weights(load_weights, model_)
