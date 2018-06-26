<?php
// index 페이지가 아닌 경우에만 출력
if(!empty($this->uri->segment(2)) && $this->uri->segment(2) != 'add') {
    //LIST (key = uri , value = TEXT)
    $addsite_progress[0]["uri"] = "region";
    $addsite_progress[0]["text"] = c2ms('progress_txt_2');

    $addsite_progress[1]["uri"] = "ssl";
    $addsite_progress[1]["text"] = c2ms('progress_txt_3');

    $addsite_progress[2]["uri"] = "manual";
    $addsite_progress[2]["text"] = c2ms('progress_txt_4');

    $addsite_progress[3]["uri"] = "dns";
    $addsite_progress[3]["text"] = c2ms('progress_txt_5');

    $addsite_progress[4]["uri"] = "complete";
    $addsite_progress[4]["text"] = c2ms('progress_txt_6');
    ?>
    <div id="addsite-progress-bar">
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="70"
                 aria-valuemin="0" aria-valuemax="100" style="width:25%">
            </div>
        </div>
        <div id="addsite-progress-text">
            <ul>
                <li class="pre"><?=c2ms('progress_txt_1');?></li>
                <?php
                $class = null;
                $dns_manual = false;
                for ($i = 0; $i < count($addsite_progress); $i++) {

                    //manual 페이지가 아니면 return
                    if (@$this->uri->segment(2) !== "manual" && $addsite_progress[$i]["uri"] === "manual") {
                        //Cloudbric DNS 페이지에서 LD01 선택한 도메인이 아닌 경우에 continue
                        if (@$this->uri->segment(2) === "dns" && $ssl_type === "LD01") {

                        } else {
                            continue;
                        }
                    }

                    if (@$this->uri->segment(2) === $addsite_progress[$i]["uri"]) {
                        $class = "active";
                    }

                    if ($class === null || $class === "pre") { //이미 지난 Step
                        $class = "pre";

                        //Cloudbric DNS 페이지에서 LD01 선택한 도메인인 경우
                        if (@$this->uri->segment(2) === "dns" && $ssl_type === "LD01") {
                            $dns_manual = true;
                        }

                    } else if ($class === "active" && @$this->uri->segment(2) !== 'complete') { //현재 Step에서 class를 빈 값으로 만들어줌. (null과 차이)
                        $class = "";
                    }


                    if ($dns_manual) {
                        echo "<li id='progress-step-manual' class='$class'>" . $addsite_progress[$i]["text"] . "</li>";
                        echo "<style>#addsite-progress-text li {width: 16.7% !important;}</style>";
                    } else {
                        echo "<li class='$class'>" . $addsite_progress[$i]["text"] . "</li>";
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
}?>