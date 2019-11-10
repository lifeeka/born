class PhpService:
    version = '${PHP_VERSION}'
    time_zone = '${TZ}'
    network = 'born'
    config = {}

    def __init__(self, project_name, env):
        self.project_name = project_name
        self.env = env

    def set_version(self, version):
        self.env.add_value("PHP_VERSION", version)

    def set_timezone(self, time_zone):
        self.env.add_value("TZ", time_zone)

    def set_network(self, network):
        self.network = network

    def create(self):
        self.config = {
            "tty": 'true',
            "build": {
                "args": {
                    "TZ": self.time_zone,
                    "PHP_VERSION": self.version
                },
                "context": "php"
            },
            "volumes": [
                "../:/app:rw"
            ]
        }

    def get_config(self):
        return self.config
