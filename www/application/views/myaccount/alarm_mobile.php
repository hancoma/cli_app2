<template>
    <div class="page" data-name="alarm">
        <div class="navbar">
            <div class="navbar-inner sliding">
                <div class="title">Notifications</div>
            </div>
        </div>
        <div class="page-content">

            <!-- Timeline -->
            <div class="timeline">

                <div class="timeline-item">
                    <div class="timeline-item-date"><?=date("j")?> <small><?=date("M")?></small></div>
                    <div class="timeline-item-divider"></div>
                    <div class="timeline-item-content">
                        <div class="timeline-item-inner bg-color-alarm">
                            <div class="timeline-item-time">12:33</div>
                            <span class="badge color-green">Visit Alarm</span>
                            <span class="badge color-black">skilltrekker.com</span>
                            <div class="timeline-item-text">Total Visits for this month has reached 90,000.</div>
                        </div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-item-date">13 <small>May</small></div>
                    <div class="timeline-item-divider"></div>
                    <div class="timeline-item-content">
                        <div class="timeline-item-inner bg-color-alarm">
                            <div class="timeline-item-time">17:20</div>
                            <span class="badge color-red">Notice</span>
                            <span class="badge color-black">genesistransformaiton.com</span>
                            <div class="timeline-item-text">We have detected more than 100 attacks today. Please check your logs and block suspicious IPs.</div>
                        </div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-item-date">11 <small>May</small></div>
                    <div class="timeline-item-divider"></div>
                    <div class="timeline-item-content">
                        <div class="timeline-item-inner bg-color-alarm">
                            <div class="timeline-item-time">01:02</div>
                            <span class="badge color-orange">Traffic Alarm</span>
                            <span class="badge color-black">skilltrekker.com</span>
                            <div class="timeline-item-text">Traffic for this month has reached 7GB.</div>
                        </div>
                        <div class="timeline-item-inner">
                            <div class="timeline-item-time">01:02</div>
                            <span class="badge color-orange">Traffic Alarm</span>
                            <span class="badge color-black">members.skilltrekker.com</span>
                            <div class="timeline-item-text">Traffic for this month has reached 4GB.</div>
                        </div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-item-date">9 <small>May</small></div>
                    <div class="timeline-item-divider"></div>
                    <div class="timeline-item-content">
                        <div class="timeline-item-inner">
                            <div class="timeline-item-time">11:50</div>
                            <span class="badge color-green">Visit Alarm</span>
                            <span class="badge color-black">greenolivetree.net</span>
                            <div class="timeline-item-text">Total Visits for this month has reached 30,000.</div>
                        </div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-item-date">8 <small>May</small></div>
                    <div class="timeline-item-divider"></div>
                    <div class="timeline-item-content">
                        <div class="timeline-item-inner">
                            <div class="timeline-item-time">19:20</div>
                            <span class="badge color-red">Notice</span>
                            <span class="badge color-black">genesistransformaiton.com</span>
                            <div class="timeline-item-text">We have detected more than 100 attacks today. Please check your logs and block suspicious IPs.</div>
                        </div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-item-date">7 <small>May</small></div>
                    <div class="timeline-item-divider"></div>
                    <div class="timeline-item-content">
                        <div class="timeline-item-inner">
                            <div class="timeline-item-time">19:42</div>
                            <span class="badge color-red">Notice</span>
                            <span class="badge color-black">genesistransformaiton.com</span>
                            <div class="timeline-item-text">We have detected more than 100 attacks today. Please check your logs and block suspicious IPs.</div>
                        </div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-item-date">5 <small>May</small></div>
                    <div class="timeline-item-divider"></div>
                    <div class="timeline-item-content">
                        <div class="timeline-item-inner">
                            <div class="timeline-item-time">19:12</div>
                            <span class="badge color-red">Notice</span>
                            <span class="badge color-black">genesistransformaiton.com</span>
                            <div class="timeline-item-text">We have detected more than 100 attacks today. Please check your logs and block suspicious IPs.</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>
