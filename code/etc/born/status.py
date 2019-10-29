import os
import socket

import requests
import yaml
from colored import fg, attr


class Status:
    def list(self):
        os.system('cd born  && docker-compose ps ')

    def domain_veryfy(self):
        os.system('cd born  && docker-compose ps ')

    def status(self):

        with open("born/config.yml", 'r') as stream:
            config = yaml.safe_load(stream)
            domains = config['domains']
            for domain in domains:
                try:
                    requests.get('http://' + domain)
                    print('%s%s%s %s %s' % ("born.local =>", fg('green'), attr('bold'), ' Active', attr(0)))
                except Exception as e:
                    print('%s%s%s %s %s' % ("born.local =>", fg('red'), attr('bold'), 'Offline', attr(0)))
