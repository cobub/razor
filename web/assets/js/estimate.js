/**
 * 
 */
function   onceavg(obj){
	 	var N = 5;
	 	var estimatearray = [];
	    for(var i=0;i<(N-1)/2;i++)
	    {		   
	    	estimatearray.push(null);	  
	    }
	    for(var i=(N-1)/2;i<obj.length-(N-1)/2;i++)
	    {		
	    	var estimatetmp = 0;
		    for(var j = i -(N-1)/2;j < i+(N+1)/2;j++)
		    {
		    	estimatetmp = estimatetmp + obj[j];
		    }   
		    estimatearray.push(parseFloat(parseFloat(estimatetmp/N).toFixed(1)));  
	    }
	    for(var i=obj.length-(N-1)/2;i<obj.length;i++)
	    {		   
	    	estimatearray.push(null);		  
	    }
   return estimatearray;
} 

function secondavg(obj,oldobj){
		var N = 5;
		var estimatearray = [];
		for(var i=0;i<(N-1);i++)
	    {		   
			estimatearray.push(null);	  
	    }
	    for(var i=(N-1);i<obj.length-(N-1);i++)
	    {		
	    	var estimatetmp = 0;
		    for(var j = i -(N-1)/2;j < i+(N+1)/2;j++)
		    {
		    	estimatetmp = estimatetmp + oldobj[j];
		    }   
		    estimatearray.push(parseFloat(parseFloat(estimatetmp/N).toFixed(1))); 	  
	    }
	    for(var i=obj.length-(N-1);i<obj.length;i++)
	    {		   
	    	estimatearray.push(null);	  
	    }
	    return estimatearray;
}