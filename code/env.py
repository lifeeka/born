import os

from PyInquirer import prompt


class Env:
    def __init__(self, file_name='.env'):
        self.file_name = file_name
        if os.path.exists(file_name):
            os.remove(file_name)

    def add_value(self, key, value):
        file = open(self.file_name, 'a')
        file.write(key + "=" + str(value) + "\n")
        file.close()
