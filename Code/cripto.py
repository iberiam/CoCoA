from Crypto import Random
from Crypto.Util.Padding import pad, unpad
import hashlib
from hashlib import sha1, md5
import hmac
import base64
from Crypto.Cipher import AES
from base64 import b64decode
from base64 import b64encode

from tokens import *

# DET encryption

def encrypt(key, value):
    return hmac.new(key.encode("utf-8"), value.encode("utf-8"), sha1).hexdigest()

# RND encryption

class AESCipher(object):

    def __init__(self, key):
        self.bs = AES.block_size
#        self.key = hashlib.sha256(key.encode()).digest()
        self.key = key[AES.block_size:].encode()      

    def encrypt(self, raw):
        iv = Random.new().read(AES.block_size)
        cipher = AES.new(self.key, AES.MODE_CBC, iv, use_aesni=True)
        return base64.b64encode(iv + cipher.encrypt(pad(raw.encode(),AES.block_size)))

    def decrypt(self, enc):
        enc = base64.b64decode(enc)
        iv = enc[:AES.block_size]
        cipher = AES.new(self.key, AES.MODE_CBC, iv, use_aesni=True)
        return unpad(cipher.decrypt(enc[AES.block_size:]),AES.block_size).decode('utf-8')




