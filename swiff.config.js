/**
 *  Swiff Project Configuration
 */
export default {
  defaultEnvironment: "staging",
  logging:{
    enabled: false,
    // change log directory (Default root: example "./storage/logs/")
    dir:'',
    // recommended 10mb max log file size than clears log
    maxSize: 10 * 1024 * 1024,
  },
  environments: {
    staging: {
     // The SSH login username
     user: "simple-craft-5",
     // The IP/hostname of the remote server
     host: "139.180.178.70",
     // The working directory of the remote app folder
     appPath: "/srv/users/simple-craft-5/apps/simple-craft-5/current",
     // The SSH port to connect on (22 is the SSH default)
     port: 22,
    },
  },
  local: {
      // Play sound for task start, message, error
	  playsound: true,
	  // If the env is ddev
	  ddev: true,
	  // Add validation check with git for folderPush makes it consistent
	  git: false
  },
  env:{
    prefix: "CRAFT_"
  },
  pushFolders: [
		"web/dist",
		"templates",
        "config",
        "simple-plugins",
        "composer.json",
        "composer.lock"
  ],
  pullFolders: [
		"web/assets",
  ],
  disabled: [ 'composerPush'],
};
