import os
import yaml
from colored import fg, attr
import status


class Station:
    def __init__(self):
        print()

    def init(self, action=False):
        directories = [d for d in os.listdir(os.getcwd()) if os.path.isdir(d)]

        for folder_name in directories:
            config_path = folder_name + '/.born/config.yml'

            if os.path.isfile(config_path):
                data = yaml.safe_load(open(config_path))

                print('%s%s%s %s %s' % (
                    attr('bold'), fg('blue'), data['project-id'] + ": ", data['project-name'], attr(0)))
                # start
                cmd = 'cd ' + folder_name + "/.born"

                if action:
                    cmd += '&& docker-compose -p ' + data['project-id'] + " " + action

                cmd += " && docker-compose -p " + data['project-id'] + " ps"
                os.system(cmd)

                try:
                    status.Status.check_status(data['domains'][0])
                except Exception as e:
                    print()

                print("\n")

    def connect(self, network_name):
        directories = [d for d in os.listdir(os.getcwd()) if os.path.isdir(d)]
        os.system("docker network create --driver bridge " + network_name + " || true")

        for folder_name in directories:
            config_path = folder_name + '/.born/config.yml'

            if os.path.isfile(config_path):
                data = yaml.safe_load(open(config_path))
                output = os.popen(
                    'cd ' + folder_name + '/.born && ' + 'docker-compose -p ' + data['project-id'] + ' ps -q').read()
                for container_id in output.splitlines():
                    print("Connecting: " + data['project-name'])
                    print("Container : " + container_id + " to " + network_name)
                    os.system("docker network connect " + network_name + " " + container_id)
