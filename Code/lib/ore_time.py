#timing tests

import time
import random
from ore_wrapper import *
bold = "\033[1m"
bold_end = "\033[0m"

N_TESTS = 10000

def test_ore(msg1, msg2):
    # setup
    sk, params = getInitiatedParams()
    
    ore_val1 = OreVal(msg1, sk, params)
    ore_val2 = OreVal(msg2, sk, params)
    # assertions
    less_than = ore_val1 < ore_val2
    
    #cleanup
    ore_val1.cleanup()
    ore_val2.cleanup()
    ore_blk_cleanup(sk)

print(bold,"Timing ORE comparisons",bold_end)
start = time.time()
for i in range(N_TESTS):
    msg1 = random.randint(0, 2**31 - 1)
    msg2 = random.randint(0, 2**31 - 1)
    
    #throws assertion error
    test_ore(msg1, msg2)
end = time.time()
ore_compare_time = end - start
print("Time taken for {} tests: {}".format(N_TESTS, ore_compare_time))
print(bold,"Timing normal comparisons",bold_end)
start = time.time()
for i in range(N_TESTS):
    msg1 = random.randint(0, 2**31 - 1)
    msg2 = random.randint(0, 2**31 - 1)
    
    msg1 < msg2
end = time.time()
normal_compare_time = end - start
print("Time taken for {} tests: {}".format(N_TESTS, normal_compare_time))
print("Ratio of ORE comparison time to normal comparison time: {}".format(ore_compare_time / normal_compare_time))

print("\n")
print(bold,"Timing normal comparisons with sorting",bold_end)
arr = [random.randint(0, 2**31 - 1) for i in range(N_TESTS)]
start = time.time()
arr.sort()
end = time.time()
normal_sort_time = end - start
print("Normal sorting time for {} values: {}".format(N_TESTS, normal_sort_time))

print(bold,"Timing ORE comparisons with sorting",bold_end)
start = time.time()
sk, params = getInitiatedParams()
enc_arr = [OreVal(msg, sk, params) for msg in arr]
enc_arr.sort()
end = time.time()
ore_sort_time = end - start
print("ORE sorting time for {} values: {}".format(N_TESTS, ore_sort_time))
print("Ratio of ORE sorting time to normal sorting time: {}".format(ore_sort_time / normal_sort_time))
