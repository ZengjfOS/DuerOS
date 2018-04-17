# DuerOS Personal DevKit Start Script

## 参考文档

* [Systemd 入门教程：实战篇](http://www.ruanyifeng.com/blog/2016/03/systemd-tutorial-part-two.html)

## duer.service

```
pi@raspberrypi:/etc/systemd $ cat system/multi-user.target.wants/duer.service
[Unit]
Description=duer main service
After=network.target

[Service]
LimitCORE=infinity
Type=forking
ExecStart=/etc/start_duer.sh
KillMode=mixed
#Restart=on-failure
Environment=LD_LIBRARY_PATH="/duer/lib"

[Install]
WantedBy=multi-user.target
pi@raspberrypi:/etc/systemd $
```

## start_duer.sh

```
#!/bin/sh

#check wlan0 interface
ifconfig | grep wlan0
if [ $? != "0" ]; then
        modprobe -r brcmfmac
        modprobe brcmfmac
fi

#play startup voice
while :

do
        aplay -Dplughw:2,0 /duer/appresources/startup.wav

        if [ $? = "0" ]; then
                break
        fi
        sleep 1s
done

export LD_LIBRARY_PATH=/duer/lib

cd /duer
./duer_daemon
```

## 相关信息

### USB设备识别

```
pi@raspberrypi:/etc $ lsusb
Bus 001 Device 004: ID 0572:1494 Conexant Systems (Rockwell), Inc.
Bus 001 Device 003: ID 0424:ec00 Standard Microsystems Corp. SMSC9512/9514 Fast Ethernet Adapter
Bus 001 Device 002: ID 0424:9514 Standard Microsystems Corp.
Bus 001 Device 001: ID 1d6b:0002 Linux Foundation 2.0 root hub
pi@raspberrypi:/etc $ lsusb
Bus 001 Device 003: ID 0424:ec00 Standard Microsystems Corp. SMSC9512/9514 Fast Ethernet Adapter
Bus 001 Device 002: ID 0424:9514 Standard Microsystems Corp.
Bus 001 Device 001: ID 1d6b:0002 Linux Foundation 2.0 root hub
```

### duer_daemon文件类型

```
pi@raspberrypi:/duer $ file duer_daemon
duer_daemon: ELF 32-bit LSB executable, ARM, EABI5 version 1 (SYSV), dynamically linked, interpreter /lib/ld-linux-armhf.so.3, for GNU/Linux 2.6.32, not stripped
pi@raspberrypi:/duer $
```
