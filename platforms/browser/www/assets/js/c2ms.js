function c2ms(key){
	var c2ms = "";

    //c2ms_list는 layout.php 에서 선언하고 있다.
	try {
        c2ms = c2ms_list[key];
        if(c2ms == undefined) c2ms = key;
	}catch(e){
        c2ms = key;
	}

	return c2ms;
}