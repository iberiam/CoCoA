from lib.ore_wrapper import OreVal


#from a encrypted vulnerability path with cifered ORE lineno decrypts the lineno's
#starts with a max_lineno to limit the search -> faster if files are small
def decrypt_lineno(results, ore_params, max_lineno):
    for i in range(len(results)):
        for j in range(len(results[i])):
            results[i][j] = list(results[i][j])
            results[i][j][1] = bounded_binary_search(results[i][j][1], ore_params, 0, max_lineno)
            results[i][j] = tuple(results[i][j]) 
    return results


#bounded binary search for decrypting ORE
#ASSUMPTION: the max value is not over 2**31-1 (regular integer limit)
def bounded_binary_search(x, ore_params, min,max):
    #if over integer limit then give up
    if max > 2**31-1:
        return -1
    l = min
    r = max
    while l < r:
        m = (l + r) // 2
        ore_m = OreVal(m, ore_params[0], ore_params[1])
        if x == ore_m:
            return m
        elif x < ore_m:
            r = m
        else:
            l = m + 1
    return bounded_binary_search(x, ore_params, max+1,max*2)

