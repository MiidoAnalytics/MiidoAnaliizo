package com.miido.analiizoOBRAS;

import android.widget.TableLayout;

import java.util.HashMap;

/**
 * Created by User on 01/02/2016.
 */
public class PollsLayouts {

    private HashMap<Long,TableLayout> views;
    private static PollsLayouts ourInstance;

    public static PollsLayouts getInstance() {
        return ourInstance;
    }

    private PollsLayouts() {
        views = new HashMap<>();
    }

    static {
        ourInstance = new PollsLayouts();
    }

    public int getViewsSize(){
        return views.size();
    }

    public TableLayout getViewById(long id){
        return views.get(id);
    }

    public void setView(long id, TableLayout view){
        views.put(id, view);
    }

    public boolean existView(long id){
        return views.containsKey(id);
    }
}
