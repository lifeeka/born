class NginxService:
    version = '${NGINX_VERSION}'
    time_zone = '${TZ}'
    port = '${NGINX_PORT}'
    network = 'born'
    config = {}

    def __init__(self, project_name, env):
        self.env = env

    def set_version(self, version):
        self.env.add_value("NGINX_VERSION", version)

    def set_network(self, network):
        self.network = network

    def create(self):
        self.config = {
            "ports": [
                "" + str(self.port) + ":80"
            ],
            "volumes": [
                "./logs/nginx/:/var/log/nginx",
                "./nginx/sites:/etc/nginx/conf.d",
                "../:/app"
            ],
            "networks": [
                self.network
            ],
            "build": {
                "args": {
                    "NGINX_VERSION": self.version,
                    "NGINX_PORT": self.port,
                    "TZ": self.time_zone
                },
                "context": "nginx"
            }
        }

    def get_config(self):
        return self.config
