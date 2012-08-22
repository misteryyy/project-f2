/* Like functionality for the site */
var Models = {
			init: function(config){
				// ajax setup
				$.ajaxSetup({
					url: "/site/index/ajax-index", 
					type: 'GET',
					dataType: 'json',
				});
				
				this.config = config;
				this.bindEvents();
			
			},
				
			bindEvents: function(){
				//this.config.likeMemberBtn.css("color","red");
				this.config.likeMemberBtn.on("click", this.likeMember);		
				this.config.likeProjectBtn.on("click", this.likeProject);		
				
			},
				
			// likeMember
			likeMember: function(e) {
				var follower_count = $(this).closest('.info').find(".follower-count");
				//console.log(follower_count);
				var val = $(this).text();
				var user_id = $(this).attr("id");
				if(val == 'Like') {
					$(this).html("Dislike");
				}else {
					$(this).html("Like");
				} 	
					// send data to server
					$.ajax({
						data:   "&_method=like-member&&friend_id="+user_id, // take data from the form and send it to the method update
					}).done(
							function (results){
								// set new value for followers		
								follower_count.html(results.data.count_friends); // number of current friends
							}
					);			
				e.preventDefault();
			},
			// likeProject
			likeProject: function(e) {
				e.preventDefault();
				var project_id = $(this).attr("id");
				var follower_count = $(this).closest('.info').find(".follower-count");
				//console.log(follower_count);
				var val = $(this).text();
				if(val == 'Like') {
					$(this).html('Dislike');
				}else {
					$(this).html('Like');
				} 	
					// send data to server
					$.ajax({
						data:   "&_method=like-project&&project_id="+project_id, // take data from the form and send it to the method update
					}).done(
							function (results){
								
								// set new value for followers		
								follower_count.html(results.data.count_followers); // number of current friends
							}
					);			
				
			}
			};
			
			// like models
			Models.init({
				
				likeMemberBtn: $(".like-member"), //select a with class 
				likeProjectBtn: $(".like-project"), //select a with class 
				 
			});
			
		