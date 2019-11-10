import os
import yaml
from colored import fg, attr
import status


class Station:
    def __init__(self):
        print()

    def start(self):
        directories = [d for d in os.listdir(os.getcwd()) if os.path.isdir(d)]

        for folder_name in directories:
            config_path = folder_name + '/.born/config.yml'

            if os.path.isfile(config_path):
                data = yaml.safe_load(open(config_path))

                # start
                print(folder_name + '/.born && docker-compose -p ' + data['project-id'] + ' up -d')
                os.system('cd ' + folder_name + '/.born && ls')
                os.system('cd ' + folder_name + '/.born && docker-compose -p ' + data['project-id'] + ' up -d')

                print('%s%s%s %s %s' % (
                    attr('bold'), fg('blue'), data['project-id'] + ": ", data['project-name'], attr(0)))

                status.Status.check_status(data['domains'][0])
                print("\n")

