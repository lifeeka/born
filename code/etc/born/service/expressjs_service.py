class ExpressJsService:
    version = '${EXPRESSJS_VERSION}'
    time_zone = '${TZ}'
    network = 'born'
    config = {}

    def __init__(self, project_name, env):
        self.project_name = project_name
        self.env = env

    def set_version(self, version):
        self.env.add_value("EXPRESSJS_VERSION", version)

    def set_network(self, network):
        self.network = network

    def create(self):
        self.config = {
            "tty": 'true',
            "networks": [
                self.network
            ],
            "volumes": [
                "../:/app:rw"
            ],
            "build": {
                "args": {
                    "TZ": self.time_zone,
                    "EXPRESSJS_VERSION": self.version,
                },
                "context": "expressjs"
            }
        }

    def get_config(self):
        return self.config
