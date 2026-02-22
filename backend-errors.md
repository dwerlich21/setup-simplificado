2026-02-22T14:20:55.5030912Z Current runner version: '2.331.0'
2026-02-22T14:20:55.5063913Z ##[group]Runner Image Provisioner
2026-02-22T14:20:55.5065102Z Hosted Compute Agent
2026-02-22T14:20:55.5066058Z Version: 20260123.484
2026-02-22T14:20:55.5067221Z Commit: 6bd6555ca37d84114959e1c76d2c01448ff61c5d
2026-02-22T14:20:55.5068328Z Build Date: 2026-01-23T19:41:17Z
2026-02-22T14:20:55.5069403Z Worker ID: {46c5bc3b-71d7-4aca-a388-e60e3ff87039}
2026-02-22T14:20:55.5070671Z Azure Region: eastus2
2026-02-22T14:20:55.5071549Z ##[endgroup]
2026-02-22T14:20:55.5074057Z ##[group]Operating System
2026-02-22T14:20:55.5075224Z Ubuntu
2026-02-22T14:20:55.5076013Z 24.04.3
2026-02-22T14:20:55.5076805Z LTS
2026-02-22T14:20:55.5077790Z ##[endgroup]
2026-02-22T14:20:55.5078586Z ##[group]Runner Image
2026-02-22T14:20:55.5079598Z Image: ubuntu-24.04
2026-02-22T14:20:55.5080470Z Version: 20260201.15.1
2026-02-22T14:20:55.5082453Z Included Software: https://github.com/actions/runner-images/blob/ubuntu24/20260201.15/images/ubuntu/Ubuntu2404-Readme.md
2026-02-22T14:20:55.5085392Z Image Release: https://github.com/actions/runner-images/releases/tag/ubuntu24%2F20260201.15
2026-02-22T14:20:55.5086958Z ##[endgroup]
2026-02-22T14:20:55.5089039Z ##[group]GITHUB_TOKEN Permissions
2026-02-22T14:20:55.5091502Z Contents: read
2026-02-22T14:20:55.5092467Z Metadata: read
2026-02-22T14:20:55.5093509Z Packages: read
2026-02-22T14:20:55.5094335Z ##[endgroup]
2026-02-22T14:20:55.5097641Z Secret source: Actions
2026-02-22T14:20:55.5099010Z Prepare workflow directory
2026-02-22T14:20:55.5668702Z Prepare all required actions
2026-02-22T14:20:55.5725092Z Getting action download info
2026-02-22T14:20:55.8202349Z Download action repository 'actions/checkout@v4' (SHA:34e114876b0b11c390a56381ad16ebd13914f8d5)
2026-02-22T14:20:55.9122444Z Download action repository 'shivammathur/setup-php@v2' (SHA:44454db4f0199b8b9685a5d763dc37cbf79108e1)
2026-02-22T14:20:56.1247966Z Download action repository 'actions/cache@v4' (SHA:0057852bfaa89a56745cba8c7296529d2fc39830)
2026-02-22T14:20:56.3188581Z Complete job name: Backend Tests
2026-02-22T14:20:56.3857574Z ##[group]Run actions/checkout@v4
2026-02-22T14:20:56.3858386Z with:
2026-02-22T14:20:56.3858812Z   repository: dwerlich21/setup-simplificado
2026-02-22T14:20:56.3859490Z   token: ***
2026-02-22T14:20:56.3859857Z   ssh-strict: true
2026-02-22T14:20:56.3860241Z   ssh-user: git
2026-02-22T14:20:56.3860624Z   persist-credentials: true
2026-02-22T14:20:56.3861052Z   clean: true
2026-02-22T14:20:56.3861439Z   sparse-checkout-cone-mode: true
2026-02-22T14:20:56.3861906Z   fetch-depth: 1
2026-02-22T14:20:56.3862281Z   fetch-tags: false
2026-02-22T14:20:56.3862673Z   show-progress: true
2026-02-22T14:20:56.3863233Z   lfs: false
2026-02-22T14:20:56.3863598Z   submodules: false
2026-02-22T14:20:56.3863984Z   set-safe-directory: true
2026-02-22T14:20:56.3864660Z ##[endgroup]
2026-02-22T14:20:56.4938956Z Syncing repository: dwerlich21/setup-simplificado
2026-02-22T14:20:56.4940686Z ##[group]Getting Git version info
2026-02-22T14:20:56.4941524Z Working directory is '/home/runner/work/setup-simplificado/setup-simplificado'
2026-02-22T14:20:56.4942557Z [command]/usr/bin/git version
2026-02-22T14:20:56.5054272Z git version 2.52.0
2026-02-22T14:20:56.5095479Z ##[endgroup]
2026-02-22T14:20:56.5113385Z Temporarily overriding HOME='/home/runner/work/_temp/6a64c2de-238d-4efb-9df7-cd00a253116f' before making global git config changes
2026-02-22T14:20:56.5114745Z Adding repository directory to the temporary git global config as a safe directory
2026-02-22T14:20:56.5129758Z [command]/usr/bin/git config --global --add safe.directory /home/runner/work/setup-simplificado/setup-simplificado
2026-02-22T14:20:56.5174251Z Deleting the contents of '/home/runner/work/setup-simplificado/setup-simplificado'
2026-02-22T14:20:56.5178859Z ##[group]Initializing the repository
2026-02-22T14:20:56.5184504Z [command]/usr/bin/git init /home/runner/work/setup-simplificado/setup-simplificado
2026-02-22T14:20:56.5405881Z hint: Using 'master' as the name for the initial branch. This default branch name
2026-02-22T14:20:56.5407854Z hint: will change to "main" in Git 3.0. To configure the initial branch name
2026-02-22T14:20:56.5409424Z hint: to use in all of your new repositories, which will suppress this warning,
2026-02-22T14:20:56.5410587Z hint: call:
2026-02-22T14:20:56.5411180Z hint:
2026-02-22T14:20:56.5411905Z hint: 	git config --global init.defaultBranch <name>
2026-02-22T14:20:56.5412962Z hint:
2026-02-22T14:20:56.5413860Z hint: Names commonly chosen instead of 'master' are 'main', 'trunk' and
2026-02-22T14:20:56.5415303Z hint: 'development'. The just-created branch can be renamed via this command:
2026-02-22T14:20:56.5416469Z hint:
2026-02-22T14:20:56.5417085Z hint: 	git branch -m <name>
2026-02-22T14:20:56.5417810Z hint:
2026-02-22T14:20:56.5418840Z hint: Disable this message with "git config set advice.defaultBranchName false"
2026-02-22T14:20:56.5420823Z Initialized empty Git repository in /home/runner/work/setup-simplificado/setup-simplificado/.git/
2026-02-22T14:20:56.5426789Z [command]/usr/bin/git remote add origin https://github.com/dwerlich21/setup-simplificado
2026-02-22T14:20:56.5470175Z ##[endgroup]
2026-02-22T14:20:56.5470922Z ##[group]Disabling automatic garbage collection
2026-02-22T14:20:56.5474791Z [command]/usr/bin/git config --local gc.auto 0
2026-02-22T14:20:56.5507807Z ##[endgroup]
2026-02-22T14:20:56.5508479Z ##[group]Setting up auth
2026-02-22T14:20:56.5515030Z [command]/usr/bin/git config --local --name-only --get-regexp core\.sshCommand
2026-02-22T14:20:56.5547008Z [command]/usr/bin/git submodule foreach --recursive sh -c "git config --local --name-only --get-regexp 'core\.sshCommand' && git config --local --unset-all 'core.sshCommand' || :"
2026-02-22T14:20:56.5932533Z [command]/usr/bin/git config --local --name-only --get-regexp http\.https\:\/\/github\.com\/\.extraheader
2026-02-22T14:20:56.5964968Z [command]/usr/bin/git submodule foreach --recursive sh -c "git config --local --name-only --get-regexp 'http\.https\:\/\/github\.com\/\.extraheader' && git config --local --unset-all 'http.https://github.com/.extraheader' || :"
2026-02-22T14:20:56.6194161Z [command]/usr/bin/git config --local --name-only --get-regexp ^includeIf\.gitdir:
2026-02-22T14:20:56.6223765Z [command]/usr/bin/git submodule foreach --recursive git config --local --show-origin --name-only --get-regexp remote.origin.url
2026-02-22T14:20:56.6466008Z [command]/usr/bin/git config --local http.https://github.com/.extraheader AUTHORIZATION: basic ***
2026-02-22T14:20:56.6501214Z ##[endgroup]
2026-02-22T14:20:56.6502469Z ##[group]Fetching the repository
2026-02-22T14:20:56.6511101Z [command]/usr/bin/git -c protocol.version=2 fetch --no-tags --prune --no-recurse-submodules --depth=1 origin +358685bffa4df0f927cceda8791097aebecac251:refs/remotes/origin/main
2026-02-22T14:20:57.0451871Z From https://github.com/dwerlich21/setup-simplificado
2026-02-22T14:20:57.0454014Z  * [new ref]         358685bffa4df0f927cceda8791097aebecac251 -> origin/main
2026-02-22T14:20:57.0489170Z ##[endgroup]
2026-02-22T14:20:57.0490477Z ##[group]Determining the checkout info
2026-02-22T14:20:57.0492359Z ##[endgroup]
2026-02-22T14:20:57.0496430Z [command]/usr/bin/git sparse-checkout disable
2026-02-22T14:20:57.0539519Z [command]/usr/bin/git config --local --unset-all extensions.worktreeConfig
2026-02-22T14:20:57.0571117Z ##[group]Checking out the ref
2026-02-22T14:20:57.0573031Z [command]/usr/bin/git checkout --progress --force -B main refs/remotes/origin/main
2026-02-22T14:20:57.1505468Z Switched to a new branch 'main'
2026-02-22T14:20:57.1507136Z branch 'main' set up to track 'origin/main'.
2026-02-22T14:20:57.1553520Z ##[endgroup]
2026-02-22T14:20:57.1556824Z [command]/usr/bin/git log -1 --format=%H
2026-02-22T14:20:57.1579179Z 358685bffa4df0f927cceda8791097aebecac251
2026-02-22T14:20:57.1836362Z ##[group]Run shivammathur/setup-php@v2
2026-02-22T14:20:57.1837562Z with:
2026-02-22T14:20:57.1838374Z   php-version: 8.2
2026-02-22T14:20:57.1839624Z   extensions: sqlite3, pdo_sqlite, mbstring, xml, ctype, json, bcmath
2026-02-22T14:20:57.1841071Z   coverage: none
2026-02-22T14:20:57.1842157Z   ini-file: production
2026-02-22T14:20:57.1843689Z   github-token: ***
2026-02-22T14:20:57.1844581Z ##[endgroup]
2026-02-22T14:20:57.2567348Z [command]/usr/bin/bash /home/runner/work/_actions/shivammathur/setup-php/v2/src/scripts/run.sh
2026-02-22T14:20:57.2971243Z
2026-02-22T14:20:57.2972951Z [90;1m==> [0m[37;1mSetup PHP[0m
2026-02-22T14:21:00.4098225Z [32;1m✓ [0m[34;1mPHP [0m[90;1mInstalled PHP 8.2.30[0m
2026-02-22T14:21:00.4098853Z
2026-02-22T14:21:00.4099160Z [90;1m==> [0m[37;1mSetup Extensions[0m
2026-02-22T14:21:00.4570548Z [32;1m✓ [0m[34;1msqlite3 [0m[90;1mEnabled[0m
2026-02-22T14:21:00.4586162Z [32;1m✓ [0m[34;1mpdo_sqlite [0m[90;1mEnabled[0m
2026-02-22T14:21:00.4618485Z [32;1m✓ [0m[34;1mmbstring [0m[90;1mEnabled[0m
2026-02-22T14:21:00.4648009Z [32;1m✓ [0m[34;1mxml [0m[90;1mEnabled[0m
2026-02-22T14:21:00.4678014Z [32;1m✓ [0m[34;1mctype [0m[90;1mEnabled[0m
2026-02-22T14:21:00.4708044Z [32;1m✓ [0m[34;1mjson [0m[90;1mEnabled[0m
2026-02-22T14:21:00.4737298Z [32;1m✓ [0m[34;1mbcmath [0m[90;1mEnabled[0m
2026-02-22T14:21:00.4738409Z
2026-02-22T14:21:00.4738742Z [90;1m==> [0m[37;1mSetup Tools[0m
2026-02-22T14:21:00.7163528Z [32;1m✓ [0m[34;1mcomposer [0m[90;1mAdded composer 2.9.3[0m
2026-02-22T14:21:00.7165223Z
2026-02-22T14:21:00.7165574Z [90;1m==> [0m[37;1mSetup Coverage[0m
2026-02-22T14:21:00.9332480Z [32;1m✓ [0m[34;1mnone [0m[90;1mDisabled Xdebug and PCOV[0m
2026-02-22T14:21:00.9333367Z
2026-02-22T14:21:00.9333669Z [90;1m==> [0m[37;1mSponsor setup-php[0m
2026-02-22T14:21:00.9334564Z [32;1m✓ [0m[34;1msetup-php [0m[90;1mhttps://setup-php.com/sponsor[0m
2026-02-22T14:21:01.0107609Z ##[group]Run actions/cache@v4
2026-02-22T14:21:01.0107881Z with:
2026-02-22T14:21:01.0108053Z   path: api/vendor
2026-02-22T14:21:01.0108401Z   key: composer-723ce6c8b822abc13ef306943153eaefd289e53c6132b2c65e85ecb8361859dd
2026-02-22T14:21:01.0108816Z   restore-keys: composer-
2026-02-22T14:21:01.0109042Z   enableCrossOsArchive: false
2026-02-22T14:21:01.0109306Z   fail-on-cache-miss: false
2026-02-22T14:21:01.0109512Z   lookup-only: false
2026-02-22T14:21:01.0109704Z   save-always: false
2026-02-22T14:21:01.0109873Z env:
2026-02-22T14:21:01.0110043Z   COMPOSER_PROCESS_TIMEOUT: 0
2026-02-22T14:21:01.0110262Z   COMPOSER_NO_INTERACTION: 1
2026-02-22T14:21:01.0110475Z   COMPOSER_NO_AUDIT: 1
2026-02-22T14:21:01.0110658Z ##[endgroup]
2026-02-22T14:21:01.2191268Z Cache not found for input keys: composer-723ce6c8b822abc13ef306943153eaefd289e53c6132b2c65e85ecb8361859dd, composer-
2026-02-22T14:21:01.2351482Z ##[group]Run composer install --no-interaction --prefer-dist --optimize-autoloader
2026-02-22T14:21:01.2352307Z [36;1mcomposer install --no-interaction --prefer-dist --optimize-autoloader[0m
2026-02-22T14:21:01.2388431Z shell: /usr/bin/bash -e {0}
2026-02-22T14:21:01.2388685Z env:
2026-02-22T14:21:01.2388867Z   COMPOSER_PROCESS_TIMEOUT: 0
2026-02-22T14:21:01.2389106Z   COMPOSER_NO_INTERACTION: 1
2026-02-22T14:21:01.2389340Z   COMPOSER_NO_AUDIT: 1
2026-02-22T14:21:01.2389557Z ##[endgroup]
2026-02-22T14:21:01.3901378Z Installing dependencies from lock file (including require-dev)
2026-02-22T14:21:01.3956326Z Verifying lock file contents can be installed on current platform.
2026-02-22T14:21:01.4371312Z Your lock file does not contain a compatible set of packages. Please run composer update.
2026-02-22T14:21:01.4371968Z
2026-02-22T14:21:01.4372086Z   Problem 1
2026-02-22T14:21:01.4373052Z     - maennchen/zipstream-php is locked to version 3.2.0 and an update of this package was not requested.
2026-02-22T14:21:01.4374349Z     - maennchen/zipstream-php 3.2.0 requires php-64bit ^8.3 -> your php-64bit version (8.2.30) does not satisfy that requirement.
2026-02-22T14:21:01.4375215Z   Problem 2
2026-02-22T14:21:01.4375884Z     - phpoffice/phpspreadsheet is locked to version 5.0.0 and an update of this package was not requested.
2026-02-22T14:21:01.4377031Z     - maennchen/zipstream-php 3.2.0 requires php-64bit ^8.3 -> your php-64bit version (8.2.30) does not satisfy that requirement.
2026-02-22T14:21:01.4378870Z     - phpoffice/phpspreadsheet 5.0.0 requires maennchen/zipstream-php ^2.1 || ^3.0 -> satisfiable by maennchen/zipstream-php[3.2.0].
2026-02-22T14:21:01.4379636Z
2026-02-22T14:21:01.4409515Z ##[error]Your lock file does not contain a compatible set of packages. Please run composer update.

  Problem 1
    - maennchen/zipstream-php is locked to version 3.2.0 and an update of this package was not requested.
    - maennchen/zipstream-php 3.2.0 requires php-64bit ^8.3 -> your php-64bit version (8.2.30) does not satisfy that requirement.
  Problem 2
    - phpoffice/phpspreadsheet is locked to version 5.0.0 and an update of this package was not requested.
    - maennchen/zipstream-php 3.2.0 requires php-64bit ^8.3 -> your php-64bit version (8.2.30) does not satisfy that requirement.
    - phpoffice/phpspreadsheet 5.0.0 requires maennchen/zipstream-php ^2.1 || ^3.0 -> satisfiable by maennchen/zipstream-php[3.2.0].

2026-02-22T14:21:01.4507163Z ##[error]Process completed with exit code 2.
2026-02-22T14:21:01.4600580Z Post job cleanup.
2026-02-22T14:21:01.5560715Z [command]/usr/bin/git version
2026-02-22T14:21:01.5596872Z git version 2.52.0
2026-02-22T14:21:01.5640338Z Temporarily overriding HOME='/home/runner/work/_temp/bdae2976-f042-4b64-bdd6-f80a84cf10d7' before making global git config changes
2026-02-22T14:21:01.5641708Z Adding repository directory to the temporary git global config as a safe directory
2026-02-22T14:21:01.5647188Z [command]/usr/bin/git config --global --add safe.directory /home/runner/work/setup-simplificado/setup-simplificado
2026-02-22T14:21:01.5685470Z [command]/usr/bin/git config --local --name-only --get-regexp core\.sshCommand
2026-02-22T14:21:01.5720987Z [command]/usr/bin/git submodule foreach --recursive sh -c "git config --local --name-only --get-regexp 'core\.sshCommand' && git config --local --unset-all 'core.sshCommand' || :"
2026-02-22T14:21:01.5956427Z [command]/usr/bin/git config --local --name-only --get-regexp http\.https\:\/\/github\.com\/\.extraheader
2026-02-22T14:21:01.5978096Z http.https://github.com/.extraheader
2026-02-22T14:21:01.5991264Z [command]/usr/bin/git config --local --unset-all http.https://github.com/.extraheader
2026-02-22T14:21:01.6022316Z [command]/usr/bin/git submodule foreach --recursive sh -c "git config --local --name-only --get-regexp 'http\.https\:\/\/github\.com\/\.extraheader' && git config --local --unset-all 'http.https://github.com/.extraheader' || :"
2026-02-22T14:21:01.6245627Z [command]/usr/bin/git config --local --name-only --get-regexp ^includeIf\.gitdir:
2026-02-22T14:21:01.6277757Z [command]/usr/bin/git submodule foreach --recursive git config --local --show-origin --name-only --get-regexp remote.origin.url
2026-02-22T14:21:01.6609912Z Cleaning up orphan processes