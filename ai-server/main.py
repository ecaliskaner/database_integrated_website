def recfib(n):
    if n < 3:
        return 1
    else:
        return recfib(n-1) + recfib(n-2)


def iterfib(n):
    if n < 3:
        return 1
    cur, pre = 1, 1
    for _ in range(n - 2):
        cur, pre = cur + pre, cur
    return cur


print(iterfib(55))