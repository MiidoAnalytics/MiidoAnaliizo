package com.miido.analiizo;


import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteException;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.view.ViewPager;
import android.support.v7.app.ActionBar;
import android.support.v7.app.ActionBarActivity;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;

import com.miido.analiizo.mcompose.Constants;
import com.miido.analiizo.util.SqlHelper;

import java.util.ArrayList;


public class StatisticsActivity extends ActionBarActivity implements ActionBar.TabListener {

    /**
     * The {@link android.support.v4.view.PagerAdapter} that will provide
     * fragments for each of the sections. We use a
     * {@link FragmentPagerAdapter} derivative, which will keep every
     * loaded fragment in memory. If this becomes too memory intensive, it
     * may be best to switch to a
     * {@link android.support.v4.app.FragmentStatePagerAdapter}.
     */
    private SectionsPagerAdapter mSectionsPagerAdapter;

    /**
     * The {@link ViewPager} that will host the section contents.
     */
    private ViewPager mViewPager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.statistics_layout);



        // Create the adapter that will return a fragment for each of the three
        // primary sections of the activity.
        mSectionsPagerAdapter = new SectionsPagerAdapter(getSupportFragmentManager());

        // Set up the ViewPager with the sections adapter.
        mViewPager = (ViewPager) findViewById(R.id.container);
        mViewPager.setAdapter(mSectionsPagerAdapter);


        // Set up the action bar.
        final ActionBar actionBar = getSupportActionBar();
        actionBar.setNavigationMode(ActionBar.NAVIGATION_MODE_TABS);

        // When swiping between different sections, select the corresponding
        // tab. We can also use ActionBar.Tab#select() to do this if we have
        // a reference to the Tab.
        mViewPager.setOnPageChangeListener(new ViewPager.SimpleOnPageChangeListener() {
            @Override
            public void onPageSelected(int position) {
                actionBar.setSelectedNavigationItem(position);
            }
        });

        // For each of the sections in the app, add a tab to the action bar.
        for (int i = 0; i < mSectionsPagerAdapter.getCount(); i++) {
            // Create a tab with text corresponding to the page title defined by
            // the adapter. Also specify this Activity object, which implements
            // the TabListener interface, as the callback (listener) for when
            // this tab is selected.
            actionBar.addTab(
                    actionBar.newTab()
                            .setIcon(i == 0 ? getResources().getDrawable(R.drawable.ic_action_persons) : getResources().getDrawable(R.drawable.ic_action_families))
                            .setText(mSectionsPagerAdapter.getPageTitle(i))
                            .setTabListener(this));
        }

    }



    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_tabs, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onTabSelected(ActionBar.Tab tab, FragmentTransaction fragmentTransaction) {
        // When the given tab is selected, switch to the corresponding page in
        // the ViewPager.
        mViewPager.setCurrentItem(tab.getPosition());
    }

    @Override
    public void onTabUnselected(ActionBar.Tab tab, FragmentTransaction fragmentTransaction) {
    }

    @Override
    public void onTabReselected(ActionBar.Tab tab, FragmentTransaction fragmentTransaction) {
    }

    public static class StatisticHolder{
        String name;
        String description;

        public static StatisticHolder newInstance() {
            StatisticHolder holder = new StatisticHolder();
            return holder;
        }
    }

    static class StatisticAdapter extends ArrayAdapter<StatisticHolder>{

        public StatisticAdapter(Context context,ArrayList<StatisticHolder> objects) {
            super(context, -1, objects);
        }

        public static StatisticAdapter newInstance(Context context,ArrayList<StatisticHolder> objects) {
            return new StatisticAdapter(context, objects);
        }

        @Override
        public View getView(int position, View convertView, ViewGroup parent) {
            View view = convertView;
            if(convertView == null){
                LayoutInflater inflater = (LayoutInflater) getContext().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
                view = inflater.inflate(R.layout.statistics_list_item, parent,false);
            }
            TextView name = (TextView) view.findViewById(R.id.statisticName);
            TextView description = (TextView) view.findViewById(R.id.statisticDescription);
            StatisticHolder holder = getItem(position);
            name.setText(holder.name);
            description.setText(holder.description);

            return view;
        }
    }

    /**
     * A placeholder fragment containing a simple view.
     */
    public static class PlaceholderFragment extends Fragment {
        /**
         * The fragment argument representing the section number for this
         * fragment.
         */
        private static final String ARG_SECTION_NUMBER = "section_number";

        public PlaceholderFragment() {
        }

        private int countQuery(Context context, String query)throws SQLiteException{
            SqlHelper sqlHelper = new SqlHelper(context);
            sqlHelper.databaseName = new Constants().responseDatabase;
            sqlHelper.OOCDB();
            sqlHelper.setQuery(new Constants().CREATE_PERSON_RESPONSE_QUERY);
            sqlHelper.execQuery();
            sqlHelper.setQuery(new Constants().CREATE_HOME_RESPONSE_QUERY);
            sqlHelper.execQuery();
            sqlHelper.setQuery(query);
            sqlHelper.execQuery();
            Cursor cursor = sqlHelper.getCursor();
            int count = 0;
            if(cursor.getCount() > 0){
                count = cursor.getInt(0);
            }
            cursor.close();
            return count;
        }

        private int getAllPersons(Context context) throws SQLiteException{
            String query = "SELECT COUNT(id) FROM person";
            return countQuery(context, query);
        }

        private int getAllFamilies(Context context) throws SQLiteException{
            String query = "SELECT COUNT(id) FROM home";
            return countQuery(context, query);
        }

        private int getSavedPersons(Context context) throws SQLiteException{
            String query = "SELECT DISTINCT COUNT(p.id) FROM person AS p, home AS h WHERE status = 1 AND p.homeid = h.id";
            return countQuery(context, query);
        }

        private int getSavedFamilies(Context contex) throws SQLiteException{
            String query = "select distinct count(id) from home where status = 1";
            return countQuery(contex, query);
        }

        // TODO: 26/07/2016 OJO 
        private int getFamiliesPausedPersons(Context context)throws SQLiteException{
            //String query = "SELECT DISTINCT COUNT(h.id) FROM person AS p, home AS h WHERE status = 0 AND p.homeid = h.id GROUP BY (h.id)";
            String query = "SELECT DISTINCT COUNT(id) FROM home WHERE status = 0";
            return countQuery(context, query);
        }

        private int getPausedPersons(Context context) throws SQLiteException {
            String query = "SELECT DISTINCT COUNT(p.id) FROM person AS p, home AS h WHERE status = 0 AND p.homeid = h.id";
            return countQuery(context, query);
        }

        private int getCurrentDayPersons(Context context)throws SQLiteException{
            String query = "select count(p.id) from home as h, person as p " +
                    "where p.homeid = h.id and  " +
                    "datetime(h.savedate) between datetime('now','start of day') and  " +
                    "datetime('now','start of day','+24 hours','-1 minutes');";
            return countQuery(context, query);
        }

        private int getCurrentDayFamilies(Context context)throws SQLiteException{
            String query = "select count(id) from home " +
                    "where datetime(savedate) between datetime('now','start of day') and  " +
                    "datetime('now','start of day','+24 hours','-1 minutes');";
            return countQuery(context, query);
        }

        private int getCurrentWeekendPersons(Context context)throws SQLiteException{
            String query = "select count(p.id) from home as h, person as p " +
                    "where p.homeid = h.id and " +
                    "date(h.savedate) between date('now','weekday 1','-7 days') and date('now');";
            return countQuery(context, query);
        }

        private int getCurrentWeekendFamilies(Context context)throws SQLiteException{
            String query = "select count(id) from home " +
                    "where date(savedate) between date('now','weekday 1','-7 days') and date('now');";
            return countQuery(context, query);
        }

        private int getLastFifthyDaysPersons(Context context)throws SQLiteException{
            String query = "select count(p.id) from home as h,person as p " +
                    "where p.homeid = h.id and date(savedate) between date('now','-15 day') and date('now');";
            return countQuery(context, query);
        }

        private int getLastFifthyDaysFamilies(Context context)throws SQLiteException{
            String query = "select count(id) from home where date(savedate) between date('now','-15 day') and date('now');";
            return countQuery(context, query);
        }

        private int getCurrentMonthPersons(Context context)throws SQLiteException{
            String query = "select count(p.id) from home as h, person as p " +
                    "where p.homeid = h.id and " +
                    "date(h.savedate) between date('now','start of month') and " +
                    "date('now','start of month','+1 month','-1 day')";
            return countQuery(context, query);
        }
        private int getCurrentMonthFamilies(Context context)throws SQLiteException{
            String query = "select count(id) from home " +
                    "where date(savedate) between date('now','start of month') and " +
                    "date('now','start of month','+1 month','-1 day')";
            return countQuery(context, query);
        }

        private int getCurrentYearPersons(Context context)throws SQLiteException{
            String query = "select distinct count(p.id) from person as p ,home as h " +
                    "where h.id = p.homeid and date(h.savedate) between " +
                    "date('now','start of year') and date('now','start of year','+1 year','-1 day');";
            return countQuery(context, query);
        }

        // TODO: 26/07/2016 OJO
        private int getCurrentYearFamilies(Context context)throws SQLiteException{
            String query = "select distinct count(id) from home " +
                    "where date(savedate) between date('now','start of year') and date('now','start of year','+1 year','-1 day')";
            return countQuery(context, query);
        }

        /**
         * Returns a new instance of this fragment for the given section
         * number.
         */
        public static PlaceholderFragment newInstance(int sectionNumber) {
            PlaceholderFragment fragment = new PlaceholderFragment();
            Bundle args = new Bundle();
            args.putInt(ARG_SECTION_NUMBER, sectionNumber);
            fragment.setArguments(args);
            return fragment;
        }

        @Override
        public View onCreateView(LayoutInflater inflater, ViewGroup container,
                                 Bundle savedInstanceState) {
            View rootView = inflater.inflate(R.layout.statistics_fragment_layout, container, false);
            ListView listView = (ListView) rootView.findViewById(R.id.statisticList);
            ArrayList<StatisticHolder> items = new ArrayList<>();
            int tabPosition = getArguments().getInt(ARG_SECTION_NUMBER) - 1;
            String[] names = tabPosition == 0 ? getResources().getStringArray(R.array.statistics_person_name) : getResources().getStringArray(R.array.statistics_family_name);
            for(int i = 0; i < names.length; i++){
                StatisticHolder holder = StatisticHolder.newInstance();
                holder.name = names[i];
                try {
                    int count = 0;
                    switch (i) {
                        case 0:
                            if (tabPosition == 0) {
                                count = getAllPersons(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_persons_sizes, count, count);
                            } else {
                                count = getAllFamilies(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_families_sizes, count, count);
                            }
                            break;
                        case 1:
                            if (tabPosition == 0) {
                                count = getSavedPersons(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_persons_sizes, count, count);
                            } else {
                                count = getFamiliesPausedPersons(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_families_sizes, count, count);
                            }
                            break;
                        case 2:
                            if (tabPosition == 0) {
                                count = getPausedPersons(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_persons_sizes, count, count);
                            }else{
                                count = getSavedFamilies(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_families_sizes, count,count);
                            }
                            break;
                        case 3:
                            if (tabPosition == 0) {
                                count = getCurrentDayPersons(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_persons_sizes, count, count);
                            }else{
                                count = getCurrentDayFamilies(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_families_sizes, count,count);
                            }
                            break;
                        case 4:
                            if (tabPosition == 0) {
                                count = getCurrentWeekendPersons(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_persons_sizes, count, count);
                            }else{
                                count = getCurrentWeekendFamilies(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_families_sizes, count,count);
                            }
                            break;
                        case 5:
                            if (tabPosition == 0) {
                                count = getLastFifthyDaysPersons(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_persons_sizes, count, count);
                            }else{
                                count = getLastFifthyDaysFamilies(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_families_sizes, count,count);
                            }
                            break;
                        case 6:
                            if (tabPosition == 0) {
                                count = getCurrentMonthPersons(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_persons_sizes, count, count);
                            }else{
                                count = getCurrentMonthFamilies(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_families_sizes, count,count);
                            }
                            break;
                        case 7:
                            if (tabPosition == 0) {
                                count = getCurrentYearPersons(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_persons_sizes, count, count);
                            }else{
                                count = getCurrentYearFamilies(inflater.getContext());
                                holder.description = getResources().getQuantityString(R.plurals.statistic_families_sizes, count,count);
                            }
                    }
                    items.add(holder);
                }catch (SQLiteException ex){
                    Log.e(getClass().getName(), ex.getMessage());
                }
            }
            StatisticAdapter adapter = StatisticAdapter.newInstance(inflater.getContext(), items);
            listView.setAdapter(adapter);
            //textView.setText(getString(R.string.section_format, getArguments().getInt(ARG_SECTION_NUMBER)));
            return rootView;
        }
    }

    /**
     * A {@link FragmentPagerAdapter} that returns a fragment corresponding to
     * one of the sections/tabs/pages.
     */
    public class SectionsPagerAdapter extends FragmentPagerAdapter {

        public SectionsPagerAdapter(FragmentManager fm) {
            super(fm);
        }

        @Override
        public Fragment getItem(int position) {
            // getItem is called to instantiate the fragment for the given page.
            // Return a PlaceholderFragment (defined as a static inner class below).
            return PlaceholderFragment.newInstance(position + 1);
        }

        @Override
        public int getCount() {
            // Show 3 total pages.
            return 2;
        }

        @Override
        public CharSequence getPageTitle(int position) {
            switch (position) {
                case 0:
                    return "Personas";
                case 1:
                    return "Familias";
            }
            return null;
        }
    }
}
