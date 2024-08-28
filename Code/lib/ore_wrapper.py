from ctypes import *
import os
import random
import sys
import time

# Load the shared library into c types.
ore = CDLL(os.path.relpath("./lib/libore.so"))


# // the public parameters for the encryption scheme, used to compare ciphertexts
# typedef struct {
#   bool initialized;    // whether or not these parameters have been initialized
#   uint32_t nbits;      // the number of bits in the plaintext elements
#   uint32_t block_len;  // the number of bits in each block of the plaintext
# } ore_blk_params[1];
class ore_blk_params(Structure):
    _fields_ = [("initialized", c_bool),
                ("nbits", c_uint32),
                ("block_len", c_uint32)]
# // the secret key for the encryption scheme
# typedef struct {
#   bool initialized;         // whether or not the secret key has been initalized
#   AES_KEY         prf_key;  // key for the PRF (for deriving the keys from each prefix)
#   AES_KEY         prp_key;  // key for the PRP (for permuting the slots within a block)
#   ore_blk_params  params;
# } ore_blk_secret_key[1];
class ore_blk_secret_key(Structure):
    _fields_ = [("initialized", c_bool),
                ("prf_key", c_uint8 * 264),
                ("prp_key", c_uint8 * 264),
                ("params", ore_blk_params)]
# // the ciphertexts of the encryption scheme
# typedef struct {
#   bool initialized;            // whether or not the ciphertext has been initialized
#   byte*           comp_left;   // the left ciphertext
#   byte*           comp_right;  // the right ciphertext
#   ore_blk_params  params;
# } ore_blk_ciphertext[1];
class ore_blk_ciphertext(Structure):
    _fields_ = [("initialized", c_bool),
                ("comp_left", POINTER(c_uint8)),
                ("comp_right", POINTER(c_uint8)),
                ("params", ore_blk_params)]

# int init_ore_blk_params(ore_blk_params params, uint32_t nbits, uint32_t block_len);
init_ore_blk_params = ore.init_ore_blk_params
init_ore_blk_params.argtypes = [POINTER(ore_blk_params), c_uint32, c_uint32]
init_ore_blk_params.restype = c_int

# int ore_blk_setup(ore_blk_secret_key sk, ore_blk_params params);
ore_blk_setup = ore.ore_blk_setup
ore_blk_setup.argtypes = [POINTER(ore_blk_secret_key), POINTER(ore_blk_params)]
ore_blk_setup.restype = c_int

# int ore_blk_cleanup(ore_blk_secret_key sk);
ore_blk_cleanup = ore.ore_blk_cleanup
ore_blk_cleanup.argtypes = [POINTER(ore_blk_secret_key)]
ore_blk_cleanup.restype = c_int

# int ore_blk_encrypt_ui(ore_blk_ciphertext ctxt, ore_blk_secret_key sk, uint64_t msg);
ore_blk_encrypt_ui = ore.ore_blk_encrypt_ui
ore_blk_encrypt_ui.argtypes = [POINTER(ore_blk_ciphertext), POINTER(ore_blk_secret_key), c_uint64]
ore_blk_encrypt_ui.restype = c_int

# int ore_blk_compare(int* result_p, ore_blk_ciphertext ctxt1, ore_blk_ciphertext ctxt2);
ore_blk_compare = ore.ore_blk_compare
ore_blk_compare.argtypes = [POINTER(c_int), POINTER(ore_blk_ciphertext), POINTER(ore_blk_ciphertext)]
ore_blk_compare.restype = c_int

# int init_ore_blk_ciphertext(ore_blk_ciphertext ctxt, ore_blk_params params);
init_ore_blk_ciphertext = ore.init_ore_blk_ciphertext
init_ore_blk_ciphertext.argtypes = [POINTER(ore_blk_ciphertext), POINTER(ore_blk_params)]
init_ore_blk_ciphertext.restype = c_int

# int clear_ore_blk_ciphertext(ore_blk_ciphertext ctxt);
clear_ore_blk_ciphertext = ore.clear_ore_blk_ciphertext
clear_ore_blk_ciphertext.argtypes = [POINTER(ore_blk_ciphertext)]
clear_ore_blk_ciphertext.restype = c_int

# int ore_blk_ciphertext_size(ore_blk_params params);
ore_blk_ciphertext_size = ore.ore_blk_ciphertext_size
ore_blk_ciphertext_size.argtypes = [POINTER(ore_blk_params)]
ore_blk_ciphertext_size.restype = c_int

#encrypted comparison with ore
class OreVal():
    def __init__(self, val, sk=None, params=None):
        if sk is None or params is None:
            #reading a previously encrypted value just so its easier to serialize and deserialize
            self.ctxt =  ore_blk_ciphertext()
            convert_bytes_to_structure(self.ctxt, bytes.fromhex(val))
            return
        self.ctxt = ore_blk_ciphertext()
        init_ore_blk_ciphertext(pointer(self.ctxt), pointer(params))
        ore_blk_encrypt_ui(pointer(self.ctxt), pointer(sk), c_uint64(val))

    #overload operators
    def __lt__(self, other):
        return self.compare(other) == -1
    def __le__(self, other):
        return self.compare(other) <= 0
    def __eq__(self, other):
        return self.compare(other) == 0
    def __ne__(self, other):
        return self.compare(other) != 0
    def __gt__(self, other):
        return self.compare(other) == 1
    def __ge__(self, other):
        return self.compare(other) >= 0
    
    def compare(self, other):
        result = c_int32()
        ore_blk_compare(byref(result), pointer(self.ctxt), pointer(other.ctxt))
        return result.value

    def cleanup(self):
        clear_ore_blk_ciphertext(pointer(self.ctxt))

    def __str__(self):
        #get the ctxt as a hex string
        ctxt_hex = convert_struct_to_bytes(self.ctxt)
        return ctxt_hex.hex()
    
    def _serialize(self):
        return str(self)
    
    @classmethod
    def _deserialize(self, text):
        return OreVal(text)

    def __hash__(self):
        return hash(str(self))
    
    def __repr__(self):
        return str(self)

    def to_bytes(self):
        return convert_struct_to_bytes(self.ctxt)


def getInitiatedParams():
    nbits = 32
    block_len = 8

    params = ore_blk_params()
    init_ore_blk_params(pointer(params), nbits, block_len)

    sk = ore_blk_secret_key()
    ore_blk_setup(pointer(sk), pointer(params))
    return sk, params

def debugSizes():
    size_of_structs()
    print("Size of ore_blk_params: ", sizeof(ore_blk_params))
    print("Size of ore_blk_ciphertext: ", sizeof(ore_blk_ciphertext))
    print("Size of ore_blk_secret_key: ", sizeof(ore_blk_secret_key))



#https://gist.github.com/lmyyao/355709b35b717c9e47c6795de7b45ccd
def convert_bytes_to_structure(st, byte):
    # sizoef(st) == sizeof(byte)
    memmove(addressof(st), byte, sizeof(st))


def convert_struct_to_bytes(st):
    buffer = create_string_buffer(sizeof(st))
    memmove(buffer, addressof(st), sizeof(st))
    return buffer.raw


def conver_int_to_bytes(number, size):
    return (number).to_bytes(size, 'big')

