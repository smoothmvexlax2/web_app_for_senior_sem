$(document).ready(function()
{
	$("ul.nav li").off('mouseenter').off('mouseleave');
	$("ul.nav li a[href='#']").each(function()
	{
		
		$(this).mouseenter(function()
		{
			$(this).children("a[href='@$$']").animate(
			{
				height: 'toggle', 
				opacity: '0.8'
			}, 2000);		
		});
		
		$(this).mouseleave(function(){
			$(this).children("ul li a[href='@$$']").animate(
			{ 
				opacity: '0.3'
			}, 1000);
			$(this).children("ul li a[href='@$$']").animate(
			{ 
				height: 'hide'
			}, 1500);			
		});
		
	}); 	
});