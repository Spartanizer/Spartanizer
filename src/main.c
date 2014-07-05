//Custom Workout Timer for Pebble. v 0.7
//By Fernando Trujano
//    trujano@mit.edu
// 6/30/2014

//App "technically" works but has many bugs and needs optimizations
/* Todo: 
Pause button
*/

#include "pebble.h"
#include<stdlib.h>
  
static Window *window;
static TextLayer *text_layer;
static AppTimer *timer;
static MenuLayer *menu_layer;
  
#define NUM_MENU_SECTIONS 1
int NUM_FIRST_MENU_ITEMS = 0 ; //# of workout saved by user (Key 1 on internal storage) // Set on main 

char * workout_names[20]; //Save up to 20 workouts

char *readFromStorage(int key) { 
  char * total; 
  total = "Error"; 
  if (persist_exists(key)){ 
    persist_read_string(key, total, 256); //Max Size  
    //APP_LOG(APP_LOG_LEVEL_DEBUG,"Reading from storage");
    //Convert int to string
      char buffer[10];
      snprintf(buffer, 10, "%d", key);  
      //APP_LOG(APP_LOG_LEVEL_DEBUG, "Buffer: %s", buffer);
    
    //APP_LOG(APP_LOG_LEVEL_DEBUG,"Total: %s", total);
    char * s = total;    
    return s; 
  }
  APP_LOG(APP_LOG_LEVEL_DEBUG,"Reading from storage: Key not found");
  return "0";  
}

void sendMessage(char* message) { 
  DictionaryIterator *iter;
 	app_message_outbox_begin(&iter);
 	dict_write_cstring(iter,1, message);
 	app_message_outbox_send();
  APP_LOG(APP_LOG_LEVEL_DEBUG,"Message \"%s\" sent to phone", message);
}

static void deleteFromStorage(int key){ 
    if (persist_exists(key)){
        char s1[12] = "delete,";
        char * s2 = readFromStorage(key);
        strcat(s1,s2);
        sendMessage(s1);  
      
        persist_delete(key); //Delete workout 
        
        for (int i = key; i < NUM_FIRST_MENU_ITEMS; i++){ 
          persist_write_string(i, readFromStorage(i+1)); //Re-arrange memory   
          APP_LOG(APP_LOG_LEVEL_DEBUG,"Setting workout: '%s' from key %i to %i", readFromStorage(i+1), i+1, i);    
        }
        //Convert int to string    
        char buffer[10];
        snprintf(buffer, 10, "%d", atoi(readFromStorage(0))-1);  
        persist_write_string(0, buffer); //Decrease workouts by one  
        APP_LOG(APP_LOG_LEVEL_DEBUG,"Key %i deleted from storage", key);   
        menu_layer_reload_data(menu_layer); //Reload the menu **(doesn't seem to work)
    }
  
  else APP_LOG(APP_LOG_LEVEL_DEBUG,"Delete from storage: Key %i not found", key);
}

void clearMemory() { 
    for (int i =0; i<NUM_FIRST_MENU_ITEMS; i++ ) { 
      deleteFromStorage(i); 
    }
    APP_LOG(APP_LOG_LEVEL_DEBUG,"Memory Cleared Successfully");
}

static Window *window;

//Stuff for Menu **** 
static uint16_t menu_get_num_sections_callback(MenuLayer *menu_layer, void *data) {
  return NUM_MENU_SECTIONS;
}

static uint16_t menu_get_num_rows_callback(MenuLayer *menu_layer, uint16_t section_index, void *data) {
  switch (section_index) {
    case 0:
      return NUM_FIRST_MENU_ITEMS;
    default:
      return 0;
  }
}

static int16_t menu_get_header_height_callback(MenuLayer *menu_layer, uint16_t section_index, void *data) {
  return MENU_CELL_BASIC_HEADER_HEIGHT; //Default Height
}

static void menu_draw_header_callback(GContext* ctx, const Layer *cell_layer, uint16_t section_index, void *data) {
  switch (section_index) {
    case 0:
      menu_cell_basic_header_draw(ctx, cell_layer, "Add timers from phone!");
      break;
  }
}

static void menu_draw_row_callback(GContext* ctx, const Layer *cell_layer, MenuIndex *cell_index, void *data) {
  switch (cell_index->section) {
    case 0:  
    //Replacing switches with for loop to dynamically add items
    for (int i=0; i<NUM_FIRST_MENU_ITEMS; i++){ 
      if (cell_index->row == i ){     
          char *s = workout_names[i];//Get Workout Title  
          menu_cell_basic_draw(ctx, cell_layer, s, "Click to start workout!", NULL);
      }
    }  
    break;    
   } 
}

// Here we capture when a user selects a menu item
void menu_select_callback(MenuLayer *menu_layer, MenuIndex *cell_index, void *data) {
  // Use the row to specify which item will receive the select action
    switch (cell_index->section) {
    case 0:
  for (int i=0; i<NUM_FIRST_MENU_ITEMS; i++){ 
      if (cell_index->row == i ){ 
        APP_LOG(APP_LOG_LEVEL_DEBUG, "Button Clicked: %i", i);
        char *workout = readFromStorage(i+1);//Get Workout Title
        sendMessage(workout); 
        //clearMemory(); 
      }
    }  
      break; 
    } 
}

//When user long click son a menu Item - aka deletes item
void menu_long_select_callback(MenuLayer *menu_layer, MenuIndex *cell_index, void *data) {
  // Use the row to specify which item will receive the select action
    switch (cell_index->section) {
    case 0:
  for (int i=0; i<NUM_FIRST_MENU_ITEMS; i++){ 
      if (cell_index->row == i ){ 
        static Window *del_window; 
        static TextLayer *del_text;
        del_window = window_create(); 
        window_stack_push(del_window, true);
        Layer *del_window_layer = window_get_root_layer(del_window);
        GRect bounds = layer_get_frame(del_window_layer); 
        del_text = text_layer_create(GRect(0, 0, bounds.size.w /* width */, 150 /* height */));
        text_layer_set_text(del_text, "Workout deleted.");
        text_layer_set_font(del_text, fonts_get_system_font(FONT_KEY_GOTHIC_24_BOLD));
        text_layer_set_text_alignment(del_text, GTextAlignmentLeft);
        layer_add_child(del_window_layer, text_layer_get_layer(del_text)); 
   
        deleteFromStorage(i+1); 
        menu_layer_reload_data(menu_layer); 
      }
    }  
      break; 
    } 
}

void window_load(Window *window) {

  Layer *window_layer = window_get_root_layer(window);
  GRect bounds = layer_get_frame(window_layer);

  menu_layer = menu_layer_create(bounds);

  menu_layer_set_callbacks(menu_layer, NULL, (MenuLayerCallbacks){
    .get_num_sections = menu_get_num_sections_callback,
    .get_num_rows = menu_get_num_rows_callback,
    .get_header_height = menu_get_header_height_callback,
    .draw_header = menu_draw_header_callback,
    .draw_row = menu_draw_row_callback,
    .select_click = menu_select_callback,
    .select_long_click = menu_long_select_callback, 
  });

  // Bind the menu layer's click config provider to the window for interactivity
  menu_layer_set_click_config_onto_window(menu_layer, window);

  // Add it to the window for display
  layer_add_child(window_layer, menu_layer_get_layer(menu_layer));
}
// End menu stuff

int timer_time = 0; 
static TextLayer *timer_text; 
char * time_str = "";

//Called every one second
static void timer_callback(void *data) {
      if (timer_time==0) { 
        vibes_long_pulse(); //Vibrate Pebble 
        window_stack_pop(false); 
        sendMessage("done"); //Go to next workout, if possible
      } 
      else { 
        timer_time--;    
        //Convert int to string 
        snprintf(time_str, 10, "%d", timer_time);
        text_layer_set_text(timer_text, time_str); //Update the time
        APP_LOG(APP_LOG_LEVEL_DEBUG,"Time left: %s",time_str);
        //APP_LOG(APP_LOG_LEVEL_DEBUG,"One Second!");
        timer = app_timer_register(1000 /* milliseconds */, timer_callback, NULL);
      }
}

//Create Timer Function 
static Window *timer_window; 
static TextLayer *title_text; 
void createTimer(char* name, char* time) {  //Creates Timer window
    APP_LOG(APP_LOG_LEVEL_DEBUG,"Creating Timer with name: %s and time: %s",name, time); 
    
    timer_window = window_create(); 
    window_stack_push(timer_window, true);
    Layer *timer_window_layer = window_get_root_layer(timer_window);
    GRect bounds = layer_get_frame(timer_window_layer);

    title_text = text_layer_create(GRect(0, 10, bounds.size.w /* width */, 28 /* height */));
    text_layer_set_text(title_text, name);
    text_layer_set_font(title_text, fonts_get_system_font(FONT_KEY_GOTHIC_24_BOLD));
    text_layer_set_text_alignment(title_text, GTextAlignmentCenter);
    layer_add_child(timer_window_layer, text_layer_get_layer(title_text));
  
    timer_text = text_layer_create(GRect(0, 60, bounds.size.w /* width */, 30 /* height */));
    text_layer_set_text(timer_text, "");
    text_layer_set_font(timer_text, fonts_get_system_font(FONT_KEY_BITHAM_30_BLACK));
    text_layer_set_text_alignment(timer_text, GTextAlignmentCenter);
    layer_add_child(timer_window_layer, text_layer_get_layer(timer_text));
  
    timer_time = atoi(time);
    timer = app_timer_register(1 /* milliseconds */, timer_callback, NULL);     
}

enum {
      AKEY_NUMBER,
      AKEY_TEXT,
   };

//Recieve message from js
static void in_received_handler(DictionaryIterator *iter, void *context) {
    APP_LOG(APP_LOG_LEVEL_DEBUG, "WatchApp Recieved Message!"); 
  
    // Check for type of message
    Tuple *text_tuple2 = dict_find(iter, AKEY_NUMBER);
    char *type; 
    type = "Error";
    if (text_tuple2) {  
      type = text_tuple2->value->cstring;
      APP_LOG(APP_LOG_LEVEL_DEBUG, "Type: %s", type );
     }
    // Check for fields you expect to receive
    Tuple *text_tuple = dict_find(iter, AKEY_TEXT);
    // Act on the found fields received
    char *message; 
    message = "Error";
    if (text_tuple) {  
      message = text_tuple->value->cstring;
      APP_LOG(APP_LOG_LEVEL_DEBUG, "Text: %s", message );
     }
  
    if (strcmp(type,"workout") == 0) { 
        APP_LOG(APP_LOG_LEVEL_DEBUG, "adding workout!");
        //Add message to storage and increase workout counter 
        persist_write_string(NUM_FIRST_MENU_ITEMS+1, message); // Add to Totalworkouts +1 
        APP_LOG(APP_LOG_LEVEL_DEBUG,"Message Added to Storage!");
      
        int totalworkouts = atoi(readFromStorage(0));
        NUM_FIRST_MENU_ITEMS = totalworkouts; //Update NUM_FIRST_MENU_ITEMS
        //Convert int to string
        char buffer[10];
        snprintf(buffer, 10, "%d", NUM_FIRST_MENU_ITEMS+1);  
        persist_write_string(0, buffer); //Increment workouts
        APP_LOG(APP_LOG_LEVEL_DEBUG,"Total Workouts %s", buffer); 
        if (atoi(buffer) > 1) menu_layer_reload_data(menu_layer);
    }
  
    else if (strcmp(type,"end") == 0) { 
        APP_LOG(APP_LOG_LEVEL_DEBUG, "Workout Finished");
        //Show end Card
        static Window *end_window; 
        static TextLayer *end_text;
        static TextLayer *end2_text;
        end_window = window_create(); 
        window_stack_push(end_window, true);
        Layer *end_window_layer = window_get_root_layer(end_window);
        GRect bounds = layer_get_frame(end_window_layer);
    
        end_text = text_layer_create(GRect(0, 10, bounds.size.w /* width */, 28 /* height */));
        text_layer_set_text(end_text, "Congratulations!");
        text_layer_set_font(end_text, fonts_get_system_font(FONT_KEY_GOTHIC_24_BOLD));
        text_layer_set_text_alignment(end_text, GTextAlignmentCenter);
        layer_add_child(end_window_layer, text_layer_get_layer(end_text));
      
        end2_text = text_layer_create(GRect(0, 60, bounds.size.w /* width */, 30 /* height */));
        text_layer_set_text(end2_text, "Workout Finished");
        text_layer_set_font(end2_text, fonts_get_system_font(FONT_KEY_GOTHIC_24_BOLD));
        text_layer_set_text_alignment(end2_text, GTextAlignmentCenter);
        layer_add_child(end_window_layer, text_layer_get_layer(end2_text));
    }
  
    else { //It is a Timer. Type = Title, message = duration
        APP_LOG(APP_LOG_LEVEL_DEBUG,"Timer message recieved with value %s", message);  
        createTimer(type,message);     
    } 
 }

void window_unload(Window *window) {
  app_timer_cancel(timer);
  // Destroy the menu layer
  menu_layer_destroy(menu_layer); 
} 

int main(void) {
  APP_LOG(APP_LOG_LEVEL_DEBUG,"C Code Started");
  
  int totalworkouts = atoi(readFromStorage(0));
  NUM_FIRST_MENU_ITEMS = totalworkouts; 
  
  //Populate workout_names array
  for (int i = 0; i<totalworkouts; i++) { 
    char * temp = readFromStorage(i+1); 
    //workout_names[i] =   temp;   // This does not work because of issues with pointers. (Every element becomes the last)
    workout_names[i]= malloc(sizeof(char)*(strlen(temp)+1)); // Save workout titles
    strcpy(workout_names[i],  temp);
  }  
   
  app_message_open(app_message_inbox_size_maximum(), app_message_outbox_size_maximum());
  app_message_register_inbox_received((AppMessageInboxReceived) in_received_handler);
 
  if (totalworkouts == 0) { 
        //Show Instructions
        static Window *ins_window; 
        static TextLayer *ins_text;
        ins_window = window_create(); 
        window_stack_push(ins_window, true);
        Layer *ins_window_layer = window_get_root_layer(ins_window);
        GRect bounds = layer_get_frame(ins_window_layer); 
        ins_text = text_layer_create(GRect(0, 0, bounds.size.w /* width */, 150 /* height */));
        text_layer_set_overflow_mode(ins_text, GTextOverflowModeWordWrap ); 
        text_layer_set_text(ins_text, "Use your phone to add workouts. On the pebble app, find this timer app and click on the settings icon. ");
        text_layer_set_font(ins_text, fonts_get_system_font(FONT_KEY_GOTHIC_24_BOLD));
        text_layer_set_text_alignment(ins_text, GTextAlignmentLeft);
        layer_add_child(ins_window_layer, text_layer_get_layer(ins_text)); 
  }
  
  else{
      window = window_create(); 
      window_set_window_handlers(window, (WindowHandlers) {
        .load = window_load,
        .unload = window_unload,
      });
      window_stack_push(window, true /* Animated */);
  }

  app_event_loop();
  text_layer_destroy(text_layer);
  window_destroy(window);
}