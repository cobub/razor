
package com.wbtech.test_sample;


import com.wbtech.ums.UmsAgent;

import android.os.Bundle;
import android.app.Activity;
import android.content.Intent;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.SeekBar;
import android.widget.SeekBar.OnSeekBarChangeListener;

//import com.wbtech.ums.UmsAgent;

public class MActivity extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_second);
        Button button = (Button)findViewById(R.id.button_back);
        SeekBar seekbar = (SeekBar)findViewById(R.id.seekBar1);
        button.setOnClickListener(new OnClickListener() {
            
            @Override
            public void onClick(View v) {
            	Intent intent = new Intent(MActivity.this,CobubSampleActivity.class);
                startActivity(intent);
                finish();
                
            }
        });
        seekbar.setOnSeekBarChangeListener(new OnSeekBarChangeListener() {
            
            
            @Override
            public void onProgressChanged(SeekBar seekBar, int progress, boolean fromUser) {
//                UmsAgent.postWebPage("Page:"+progress);
                
            }

            @Override
            public void onStartTrackingTouch(SeekBar seekBar) {
                // TODO Auto-generated method stub
                
            }

            @Override
            public void onStopTrackingTouch(SeekBar seekBar) {
                // TODO Auto-generated method stub
                
            }
        });
        
    }
    
    @Override
    protected void onResume() {
        super.onResume();
        String  name = this.getComponentName()
				.getShortClassName();
        System.out.println(name);
        UmsAgent.onResume(this);
    }
    
    

    @Override
    protected void onPause() {
       
        super.onPause();
        UmsAgent.onPause(this);
    }
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.second, menu);
        return true;
    }

}
