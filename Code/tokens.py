class MyToken(object):
    def __init__(self, type, lineno):
        self.type = type
        self.lineno = lineno

    def __repr__(self):
        return f'MyToken({self.type}, {self.lineno})'
    
    def __eq__(self, __value: object) -> bool:
        return self.__repr__() == __value.__repr__()
    
    def __ne__(self, __value: object) -> bool:
        return self.__repr__() != __value.__repr__()
    
    def __hash__(self) -> int:
        return hash(self.__repr__())

    def copy(self): 
        return MyToken(self.type, self.lineno)
