import os


class Build:
    def __init__(self, project_name):
        self.project_name = project_name

    def build(self, force=False):
        if force:
            os.system('cd born  && docker-compose down && docker-compose build --no-cache ')
        else:
            os.system('cd born && docker-compose build')

    def up(self):
        command = os.system('cd born && docker-compose  up -d')
