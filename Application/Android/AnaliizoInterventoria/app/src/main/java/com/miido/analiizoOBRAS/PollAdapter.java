package com.miido.analiizoOBRAS;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.TextView;

import com.miido.analiizoOBRAS.model.Poll;

import java.util.ArrayList;

/**
 * Apdapta los elementos de una lista ListView a Objetos compuestos, este caso a una encuesta Poll
 * @author Ing. Miguel Angel Urango Blanco MIIDO S.A.S  05/01/2016.
 * @version 1.0
 * @see ArrayAdapter
 * @see Poll
 */
public class PollAdapter extends ArrayAdapter<Poll> {

    private Context context;
    private ArrayList<Poll> values;
    private View.OnClickListener onClickListener;

    /**
     * Constructor de la clase
     * @param context contexto de la en el que se llama al objeto
     * @param values Arreglo dinamico que contiene las encuestas a mostrar en el ListView
     * @param onClickListener listener o evento al que respondera un botón que desplegará mas opciones para cada obejeto de la lista
     * @see ArrayList
     * @see Poll
     */
    public PollAdapter(Context context,ArrayList<Poll> values,View.OnClickListener onClickListener){
        super(context, -1, values);
        this.context = context;
        this.values = values;
        this.onClickListener = onClickListener;
    }

    /**
     * Método heredado de ArrayAdapter que se ejecuta cada vez que se selecciona un lemento de la lista y cuado se genera la lista por primera vez
     * @param position posición del elemento en la lista
     * @param convertView la vista del elemento
     * @param viewGroup el grupo de la vista
     * @return ala vista del elemento de la lista
     */
    @Override
    public View getView(int position, View convertView, ViewGroup viewGroup){
        View rowView = convertView;

        if(rowView == null){
            LayoutInflater layoutInflater = (LayoutInflater) this.context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            rowView = layoutInflater.inflate(R.layout.listview_item_new, viewGroup, false);
        }

        ImageView pollIcon = (ImageView) rowView.findViewById(R.id.pollicon);
        ImageButton popupButton = (ImageButton) rowView.findViewById(R.id.menuButton);
        TextView pollTitle = (TextView) rowView.findViewById(R.id.polltitle);
        TextView pollDescription = (TextView) rowView.findViewById(R.id.polldescription);

        popupButton.setTag(getItem(position));
        popupButton.setOnClickListener(this.onClickListener);

        pollIcon.setImageResource(R.drawable.ic_poll_item);
        pollTitle.setText(this.values.get(position).getTitle());
        pollDescription.setText(this.values.get(position).getProjectName());

        return rowView;
    }

    /**
     * Retorna todos los objetos de la lista.
     * @return Arreglo que contiene las encuestas desplegadas el las lista
     * @see ArrayList
     * @see Poll
     */
    public ArrayList<Poll> items(){
        return values;
    }

}
