BAE_Git_URL = https://git.bce.baidu.com/baeapp-t2w8zrkb8njs.git
GitHub_URL = https://github.com/ZengjfOS/DuerOS.git
GitHub_Branch = WebColor

# BAE_Type = base
BAE_Type = pro

all:
	git clone $(GitHub_URL) --branch $(GitHub_Branch) --single-branch $(GitHub_Branch)
	git clone $(BAE_Git_URL) --branch master bae_git
	cp -vr $(GitHub_Branch)/* bae_git
