import os

import manage


class Build:
    def __init__(self, project_name):
        self.project_name = project_name
        self.project = manage.Manage()

    def build(self, force=False):
        if force:
            os.system(
                'cd born  && docker-compose -p ' + self.project.get_project_id() + ' down && docker-compose  -p ' + self.project.get_project_id() + ' build --no-cache')
        else:
            os.system('cd born && docker-compose build')

    def up(self):
        os.system('cd born && docker-compose -p ' + self.project.get_project_id() + ' up -d')
