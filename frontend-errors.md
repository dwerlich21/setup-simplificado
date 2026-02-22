2026-02-22T14:20:56.2126326Z Current runner version: '2.331.0'
2026-02-22T14:20:56.2149065Z ##[group]Runner Image Provisioner
2026-02-22T14:20:56.2149821Z Hosted Compute Agent
2026-02-22T14:20:56.2150471Z Version: 20260123.484
2026-02-22T14:20:56.2151027Z Commit: 6bd6555ca37d84114959e1c76d2c01448ff61c5d
2026-02-22T14:20:56.2151718Z Build Date: 2026-01-23T19:41:17Z
2026-02-22T14:20:56.2152376Z Worker ID: {c07b31c6-b000-4358-b03c-652eb4abf929}
2026-02-22T14:20:56.2153104Z Azure Region: westus
2026-02-22T14:20:56.2153592Z ##[endgroup]
2026-02-22T14:20:56.2155027Z ##[group]Operating System
2026-02-22T14:20:56.2155644Z Ubuntu
2026-02-22T14:20:56.2156069Z 24.04.3
2026-02-22T14:20:56.2156584Z LTS
2026-02-22T14:20:56.2157049Z ##[endgroup]
2026-02-22T14:20:56.2157726Z ##[group]Runner Image
2026-02-22T14:20:56.2158257Z Image: ubuntu-24.04
2026-02-22T14:20:56.2158846Z Version: 20260201.15.1
2026-02-22T14:20:56.2159976Z Included Software: https://github.com/actions/runner-images/blob/ubuntu24/20260201.15/images/ubuntu/Ubuntu2404-Readme.md
2026-02-22T14:20:56.2161483Z Image Release: https://github.com/actions/runner-images/releases/tag/ubuntu24%2F20260201.15
2026-02-22T14:20:56.2162359Z ##[endgroup]
2026-02-22T14:20:56.2163445Z ##[group]GITHUB_TOKEN Permissions
2026-02-22T14:20:56.2165447Z Contents: read
2026-02-22T14:20:56.2165961Z Metadata: read
2026-02-22T14:20:56.2166557Z Packages: read
2026-02-22T14:20:56.2167038Z ##[endgroup]
2026-02-22T14:20:56.2169212Z Secret source: Actions
2026-02-22T14:20:56.2169992Z Prepare workflow directory
2026-02-22T14:20:56.2555866Z Prepare all required actions
2026-02-22T14:20:56.2593817Z Getting action download info
2026-02-22T14:20:56.7124069Z Download action repository 'actions/checkout@v4' (SHA:34e114876b0b11c390a56381ad16ebd13914f8d5)
2026-02-22T14:20:56.8037613Z Download action repository 'actions/setup-node@v4' (SHA:49933ea5288caeca8642d1e84afbd3f7d6820020)
2026-02-22T14:20:56.9887737Z Complete job name: Frontend Tests
2026-02-22T14:20:57.0572538Z ##[group]Run actions/checkout@v4
2026-02-22T14:20:57.0573364Z with:
2026-02-22T14:20:57.0573801Z   repository: dwerlich21/setup-simplificado
2026-02-22T14:20:57.0574533Z   token: ***
2026-02-22T14:20:57.0574934Z   ssh-strict: true
2026-02-22T14:20:57.0575417Z   ssh-user: git
2026-02-22T14:20:57.0575902Z   persist-credentials: true
2026-02-22T14:20:57.0576328Z   clean: true
2026-02-22T14:20:57.0576715Z   sparse-checkout-cone-mode: true
2026-02-22T14:20:57.0577175Z   fetch-depth: 1
2026-02-22T14:20:57.0577708Z   fetch-tags: false
2026-02-22T14:20:57.0578090Z   show-progress: true
2026-02-22T14:20:57.0578481Z   lfs: false
2026-02-22T14:20:57.0578829Z   submodules: false
2026-02-22T14:20:57.0579222Z   set-safe-directory: true
2026-02-22T14:20:57.0579923Z ##[endgroup]
2026-02-22T14:20:57.1666568Z Syncing repository: dwerlich21/setup-simplificado
2026-02-22T14:20:57.1669543Z ##[group]Getting Git version info
2026-02-22T14:20:57.1670954Z Working directory is '/home/runner/work/setup-simplificado/setup-simplificado'
2026-02-22T14:20:57.1672821Z [command]/usr/bin/git version
2026-02-22T14:20:57.1751663Z git version 2.52.0
2026-02-22T14:20:57.1778368Z ##[endgroup]
2026-02-22T14:20:57.1794221Z Temporarily overriding HOME='/home/runner/work/_temp/9e873531-ab09-4988-9b08-7fd368dae242' before making global git config changes
2026-02-22T14:20:57.1796567Z Adding repository directory to the temporary git global config as a safe directory
2026-02-22T14:20:57.1808898Z [command]/usr/bin/git config --global --add safe.directory /home/runner/work/setup-simplificado/setup-simplificado
2026-02-22T14:20:57.1848528Z Deleting the contents of '/home/runner/work/setup-simplificado/setup-simplificado'
2026-02-22T14:20:57.1853073Z ##[group]Initializing the repository
2026-02-22T14:20:57.1857069Z [command]/usr/bin/git init /home/runner/work/setup-simplificado/setup-simplificado
2026-02-22T14:20:57.1961631Z hint: Using 'master' as the name for the initial branch. This default branch name
2026-02-22T14:20:57.1962726Z hint: will change to "main" in Git 3.0. To configure the initial branch name
2026-02-22T14:20:57.1963860Z hint: to use in all of your new repositories, which will suppress this warning,
2026-02-22T14:20:57.1964533Z hint: call:
2026-02-22T14:20:57.1964875Z hint:
2026-02-22T14:20:57.1965462Z hint: 	git config --global init.defaultBranch <name>
2026-02-22T14:20:57.1966017Z hint:
2026-02-22T14:20:57.1966532Z hint: Names commonly chosen instead of 'master' are 'main', 'trunk' and
2026-02-22T14:20:57.1968081Z hint: 'development'. The just-created branch can be renamed via this command:
2026-02-22T14:20:57.1968835Z hint:
2026-02-22T14:20:57.1969200Z hint: 	git branch -m <name>
2026-02-22T14:20:57.1969611Z hint:
2026-02-22T14:20:57.1970168Z hint: Disable this message with "git config set advice.defaultBranchName false"
2026-02-22T14:20:57.1971235Z Initialized empty Git repository in /home/runner/work/setup-simplificado/setup-simplificado/.git/
2026-02-22T14:20:57.1979127Z [command]/usr/bin/git remote add origin https://github.com/dwerlich21/setup-simplificado
2026-02-22T14:20:57.2013882Z ##[endgroup]
2026-02-22T14:20:57.2015095Z ##[group]Disabling automatic garbage collection
2026-02-22T14:20:57.2019223Z [command]/usr/bin/git config --local gc.auto 0
2026-02-22T14:20:57.2048263Z ##[endgroup]
2026-02-22T14:20:57.2049461Z ##[group]Setting up auth
2026-02-22T14:20:57.2055596Z [command]/usr/bin/git config --local --name-only --get-regexp core\.sshCommand
2026-02-22T14:20:57.2085938Z [command]/usr/bin/git submodule foreach --recursive sh -c "git config --local --name-only --get-regexp 'core\.sshCommand' && git config --local --unset-all 'core.sshCommand' || :"
2026-02-22T14:20:57.2545965Z [command]/usr/bin/git config --local --name-only --get-regexp http\.https\:\/\/github\.com\/\.extraheader
2026-02-22T14:20:57.2576740Z [command]/usr/bin/git submodule foreach --recursive sh -c "git config --local --name-only --get-regexp 'http\.https\:\/\/github\.com\/\.extraheader' && git config --local --unset-all 'http.https://github.com/.extraheader' || :"
2026-02-22T14:20:57.2814354Z [command]/usr/bin/git config --local --name-only --get-regexp ^includeIf\.gitdir:
2026-02-22T14:20:57.2850789Z [command]/usr/bin/git submodule foreach --recursive git config --local --show-origin --name-only --get-regexp remote.origin.url
2026-02-22T14:20:57.3110368Z [command]/usr/bin/git config --local http.https://github.com/.extraheader AUTHORIZATION: basic ***
2026-02-22T14:20:57.3146765Z ##[endgroup]
2026-02-22T14:20:57.3149177Z ##[group]Fetching the repository
2026-02-22T14:20:57.3156730Z [command]/usr/bin/git -c protocol.version=2 fetch --no-tags --prune --no-recurse-submodules --depth=1 origin +358685bffa4df0f927cceda8791097aebecac251:refs/remotes/origin/main
2026-02-22T14:20:57.9875561Z From https://github.com/dwerlich21/setup-simplificado
2026-02-22T14:20:57.9877981Z  * [new ref]         358685bffa4df0f927cceda8791097aebecac251 -> origin/main
2026-02-22T14:20:57.9909540Z ##[endgroup]
2026-02-22T14:20:57.9912442Z ##[group]Determining the checkout info
2026-02-22T14:20:57.9913875Z ##[endgroup]
2026-02-22T14:20:57.9917825Z [command]/usr/bin/git sparse-checkout disable
2026-02-22T14:20:57.9960329Z [command]/usr/bin/git config --local --unset-all extensions.worktreeConfig
2026-02-22T14:20:57.9985704Z ##[group]Checking out the ref
2026-02-22T14:20:57.9989295Z [command]/usr/bin/git checkout --progress --force -B main refs/remotes/origin/main
2026-02-22T14:20:58.0913233Z Switched to a new branch 'main'
2026-02-22T14:20:58.0914397Z branch 'main' set up to track 'origin/main'.
2026-02-22T14:20:58.0926938Z ##[endgroup]
2026-02-22T14:20:58.0963715Z [command]/usr/bin/git log -1 --format=%H
2026-02-22T14:20:58.0986373Z 358685bffa4df0f927cceda8791097aebecac251
2026-02-22T14:20:58.1273893Z ##[group]Run actions/setup-node@v4
2026-02-22T14:20:58.1274730Z with:
2026-02-22T14:20:58.1275244Z   node-version: 20
2026-02-22T14:20:58.1275847Z   cache: yarn
2026-02-22T14:20:58.1276482Z   cache-dependency-path: front/yarn.lock
2026-02-22T14:20:58.1277626Z   always-auth: false
2026-02-22T14:20:58.1278237Z   check-latest: false
2026-02-22T14:20:58.1279101Z   token: ***
2026-02-22T14:20:58.1279895Z ##[endgroup]
2026-02-22T14:20:58.3032979Z Found in cache @ /opt/hostedtoolcache/node/20.20.0/x64
2026-02-22T14:20:58.3041550Z ##[group]Environment details
2026-02-22T14:20:58.6714400Z node: v20.20.0
2026-02-22T14:20:58.6715443Z npm: 10.8.2
2026-02-22T14:20:58.6716262Z yarn: 1.22.22
2026-02-22T14:20:58.6718506Z ##[endgroup]
2026-02-22T14:20:58.6777681Z [command]/usr/local/bin/yarn --version
2026-02-22T14:20:58.7914133Z 1.22.22
2026-02-22T14:20:58.8007758Z [command]/usr/local/bin/yarn cache dir
2026-02-22T14:20:58.9412765Z /home/runner/.cache/yarn/v6
2026-02-22T14:20:58.9597538Z [command]/usr/local/bin/yarn config get enableGlobalCache
2026-02-22T14:20:59.1014629Z undefined
2026-02-22T14:20:59.3966628Z yarn cache is not found
2026-02-22T14:20:59.4078757Z ##[group]Run yarn install --frozen-lockfile
2026-02-22T14:20:59.4079156Z [36;1myarn install --frozen-lockfile[0m
2026-02-22T14:20:59.4124514Z shell: /usr/bin/bash -e {0}
2026-02-22T14:20:59.4124800Z ##[endgroup]
2026-02-22T14:20:59.5385111Z yarn install v1.22.22
2026-02-22T14:20:59.5847020Z [1/4] Resolving packages...
2026-02-22T14:20:59.7298917Z [2/4] Fetching packages...
2026-02-22T14:21:12.2866420Z error @nuxt/kit@3.0.0: The engine "node" is incompatible with this module. Expected version "^14.16.0 || ^16.10.0 || ^17.0.0 || ^18.0.0 || ^19.0.0". Got "20.20.0"
2026-02-22T14:21:12.2939384Z error Found incompatible module.
2026-02-22T14:21:12.2940871Z info Visit https://yarnpkg.com/en/docs/cli/install for documentation about this command.
2026-02-22T14:21:12.3203832Z ##[error]Process completed with exit code 1.
2026-02-22T14:21:12.3388856Z Post job cleanup.
2026-02-22T14:21:12.4314569Z [command]/usr/bin/git version
2026-02-22T14:21:12.4355347Z git version 2.52.0
2026-02-22T14:21:12.4399069Z Temporarily overriding HOME='/home/runner/work/_temp/9e154743-8321-4ff2-9885-acc6c8b22fb2' before making global git config changes
2026-02-22T14:21:12.4400675Z Adding repository directory to the temporary git global config as a safe directory
2026-02-22T14:21:12.4405831Z [command]/usr/bin/git config --global --add safe.directory /home/runner/work/setup-simplificado/setup-simplificado
2026-02-22T14:21:12.4440377Z [command]/usr/bin/git config --local --name-only --get-regexp core\.sshCommand
2026-02-22T14:21:12.4472960Z [command]/usr/bin/git submodule foreach --recursive sh -c "git config --local --name-only --get-regexp 'core\.sshCommand' && git config --local --unset-all 'core.sshCommand' || :"
2026-02-22T14:21:12.4696997Z [command]/usr/bin/git config --local --name-only --get-regexp http\.https\:\/\/github\.com\/\.extraheader
2026-02-22T14:21:12.4716745Z http.https://github.com/.extraheader
2026-02-22T14:21:12.4729134Z [command]/usr/bin/git config --local --unset-all http.https://github.com/.extraheader
2026-02-22T14:21:12.4758530Z [command]/usr/bin/git submodule foreach --recursive sh -c "git config --local --name-only --get-regexp 'http\.https\:\/\/github\.com\/\.extraheader' && git config --local --unset-all 'http.https://github.com/.extraheader' || :"
2026-02-22T14:21:12.4973860Z [command]/usr/bin/git config --local --name-only --get-regexp ^includeIf\.gitdir:
2026-02-22T14:21:12.5003133Z [command]/usr/bin/git submodule foreach --recursive git config --local --show-origin --name-only --get-regexp remote.origin.url
2026-02-22T14:21:12.5333137Z Cleaning up orphan processes