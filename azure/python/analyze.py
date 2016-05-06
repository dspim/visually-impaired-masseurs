
# coding: utf-8

# In[2]:

#!/usr/bin/python

import argparse
import pymysql
import plotly
# plotly.offline.init_notebook_mode()
from plotly.graph_objs import *
from plotly.offline.offline import _plot_html
from datetime import date,datetime

parser = argparse.ArgumentParser(description='Analyze ')
parser.add_argument("--compare", help="Compare attributes, including assigned, not_assigned, guest. ex.: --compare assigned,not_assigned")
parser.add_argument("--fromDate", help="From date for target's period or between's period. ex.: --from 2015-04-01")
parser.add_argument("--toDate", help="Last date for target's period. ex.: --to 2015-04-01")
parser.add_argument("--step", help="Select one time step format for between's period, including week, month, season. ex.: --step season")

parser.add_argument("--between", help="Select one between attributes, including masseur, helper, shop. ex.: --between masseur", default="masseur")

parser.add_argument("--masseur", help="Target masseur Name", default="")
parser.add_argument("--helper", help="Target helper Name", default="")
parser.add_argument("--shop", help="Target shop Name", default="")

parser.add_argument("--by", help="Select one aggregate argument, including sum, count, average. ex.: --by sum", default="sum")

parser.add_argument("--barMode", help="Select one bar char mode , including stack,group. ex.: --by sum", default="group")

args = parser.parse_args()
if args.compare != None:
    compares = args.compare.split(',')
target_period = None
between = args.between
if args.fromDate != None and args.toDate != None:
    target_period = {"from":datetime.strptime(args.fromDate,"%Y-%m-%d").date(), "to":datetime.strptime(args.toDate,"%Y-%m-%d").date()}
elif args.fromDate != None and args.step != None:
    between = {"from":args.fromDate, "step":args.step}

def query(compares, targets, between, by, barMode):
    # compares = ['assigned', 'not_assigned', 'guest']
    # target = {"m":"","h":"","s": "","p":""}
    # between = 'masseur'|'helper'|'shop'|'date'
    conn = pymysql.connect(host='ap-cdbr-azure-east-c.cloudapp.net', port=3306, user='b4aa79b2c77ddc', passwd='23d314ad', db='D4SG_VIM',charset='utf8')
    cur = conn.cursor()
    cur.execute("SELECT * FROM worklog")  
    worklogs = list(cur.fetchall())
#     print date(2015,4,12) > worklogs[0][7]
    cur.execute("SELECT * FROM masseur")
    masseurs = list(cur.fetchall())
    cur.execute("SELECT * FROM helper")
    helpers = list(cur.fetchall())
    cur.execute("SELECT * FROM shop")
    shops = list(cur.fetchall())
    
    # find target 
    target_masseurs = masseurs
    target_helpers = helpers
    target_shops = shops
    target_worklogs = None
    timeSeriesPlot = False
    if targets["m"] != "":
        target_masseurs = filter(lambda x: x[1] == targets["m"].decode("utf-8"), target_masseurs)
        for i in target_masseurs:
            worklogs = filter(lambda x: x[1] == i[0], worklogs)
    if targets["h"] != "":
        target_helpers = filter(lambda x: x[1] == targets["h"].decode("utf-8"), target_helpers)
        for i in target_helpers:
            worklogs = filter(lambda x: x[2] == i[0], worklogs)
    if targets["s"] != "":
        target_shops = filter(lambda x: x[1] == targets["s"].decode("utf-8"), target_shops)
        for i in target_shops:
            worklogs = filter(lambda x: x[3] == i[0], worklogs)
    fromDate = None
    toDate = None
    if targets["p"] != None:
        timeSeriesPlot = True
        fromDate = targets["p"]["from"]
        toDate = targets["p"]["to"]
        worklogs = filter(lambda x: x[7] >= fromDate and x[7] <= toDate, worklogs)
        
    dic = {}
    
    # between condition
    table = {"assigned":4, "not_assigned": 5, "guest": 6}
    if between == "masseur":
        for masseur in target_masseurs:
            mid = masseur[0]
            dic[masseur[1]] = {}
            for c in compares:
                count = reduce(lambda x, y: x + y[table[c]] if y[1] == mid else x, worklogs, 0)
                dic[masseur[1]][c] = count
    elif between == "helper":
        for helper in target_helpers:
            hid = helper[0]
            dic[helper[1]] = {}
            for c in compares:
                count = reduce(lambda x, y: x + y[table[c]] if y[2] == hid else x, worklogs, 0)
                dic[helper[1]][c] = count
    elif between == "shop":
        for shop in target_shops:
            sid = shop[0]
            dic[shop[1]] = {}
            for c in compares:
                count = reduce(lambda x, y: x + y[table[c]] if y[3] == sid else x, worklogs, 0)
                dic[shop[1]][c] = count
#     elif between == "date":
#         something to do
    # compare result and create plot
    data = []
    if timeSeriesPlot:
        for c in compares:
            trace = Scatter(
                x=[mname for mname in dic.keys() ],
                y=[m[c] for m in dic.values()],
                name = c
            )
            data.append(trace)
        layout = dict(
            title='Time series with range slider and selectors',
            xaxis=dict(
                rangeselector=dict(
                    buttons=list([
                        dict(count=1,
                            label='1m',
                            step='month',
                            stepmode='backward'),
                        dict(count=6,
                            label='6m',
                            step='month',
                            stepmode='backward'),
                        dict(count=1,
                            label='YTD',
                            step='year',
                            stepmode='todate'),
                        dict(count=1,
                            label='1y',
                            step='year',
                            stepmode='backward'),
                        dict(step='all')
                    ])
                ),rangeslider=dict(),type='date')
            )
        fig = Figure(data=data, layout=layout)
        plot_html, plotdivid, width, height = _plot_html(fig, False, "", True, '100%', 525, False)
        print plot_html
    else:
        for c in compares:
            trace = Bar(
                x=[mname for mname in dic.keys() ],
                y=[m[c] for m in dic.values()],
                name = c
            )
            data.append(trace)
        
        layout = Layout(
            barmode= barMode
        )
        fig = Figure(data=data, layout=layout)
        plot_html, plotdivid, width, height = _plot_html(fig, False, "", True, '100%', 525, False)
        print plot_html
    cur.close()
    conn.close()
    
query(compares, {"m":args.masseur,"h":args.helper,"s":args.shop,"p":target_period}, between, args.by, args.barMode)


# In[ ]:



