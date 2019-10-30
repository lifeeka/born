import os
import socket
import sys

import yaml

import status


class Manage:
    config = {}

    def __init__(self):
        if os.path.isdir("born") and os.path.isfile('born/config.yml'):
            self.project_exist = True
        else:
            self.project_exist = False

    def is_initialized(self):
        return self.project_exist

    def load_config(self):
        with open("born/config.yml", 'r') as stream:
            try:
                self.config = yaml.safe_load(stream)
            except yaml.YAMLError as exc:
                print(exc)

    def get_project_name(self):
        self.load_config()
        return self.config['project-name']

    def get_project_id(self):
        self.load_config()
        return self.config['project-id']

    def up(self):
        self.load_config()
        os.system('cd born && docker-compose -p ' + self.get_project_id() + ' up -d')
        self.list()

    def down(self):
        self.load_config()
        os.system('cd born && docker-compose -p ' + self.get_project_id() + ' down')

    def remove(self):
        self.load_config()
        os.system('cd born && docker-compose -p ' + self.get_project_id() + ' rm')

    def list(self):
        self.load_config()
        os.system('cd born  && docker-compose -p ' + self.get_project_id() + ' ps ')

    @staticmethod
    def get_random_port():
        ip = socket.gethostbyname("0.0.0.0")
        try:
            for port in range(10000, 50000):
                sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                result = sock.connect_ex((ip, port))
                if result != 0:
                    return port
                sock.close()

        except KeyboardInterrupt:
            print("You pressed Ctrl+C")
            sys.exit()
