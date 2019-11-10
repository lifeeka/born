import os
import yaml
from colored import fg, attr
import status


class Station:
    def __init__(self):
        print()

    def init(self, action='start'):
        directories = [d for d in os.listdir(os.getcwd()) if os.path.isdir(d)]

        for folder_name in directories:
            config_path = folder_name + '/.born/config.yml'

            if os.path.isfile(config_path):
                data = yaml.safe_load(open(config_path))

                # start
                cmd = 'cd ' + folder_name + '/.born && docker-compose -p ' + data['project-name'] + " " + action
                print('%s Executing: %s' + cmd % (fg(1), attr(1)))
                os.system(cmd)

                print('%s%s%s %s %s' % (
                    attr('bold'), fg('blue'), data['project-id'] + ": ", data['project-name'], attr(0)))

                status.Status.check_status(data['domains'][0])
                print("\n")
