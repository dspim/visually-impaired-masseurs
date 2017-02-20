
# coding: utf-8

# In[2]:

#!/usr/bin/python

import argparse
import pymysql
import itertools
import plotly
import calendar
import json
# plotly.offline.init_notebook_mode()
from plotly.graph_objs import *
from plotly.offline.offline import _plot_html
from datetime import date, datetime, timedelta

table = {"assigned":4, "not_assigned": 5, "guest": 6}
EnglishToChinese = {"assigned":"指定節數", "not_assigned": "未指定節數", "guest": "來客數", "assigned_not_assigned":"總節數"}

def apply_lambda(compares):
    if len(compares) == 1:
        if compares[0] == "assigned_not_assigned":
            return (lambda x, y: x + y[table["assigned"]] + y[table["not_assigned"]])
        else:
            return (lambda x, y: x + y[table[compares[0]]])
    elif "assigned" in compares and "not_assigned" in compares:
        return (lambda x, y: x + y[table["assigned"]] + y[table["not_assigned"]])
def add_months(sourcedate,months):
    month = sourcedate.month - 1 + months
    year = int(sourcedate.year + month / 12 )
    month = month % 12 + 1
    day = min(sourcedate.day,calendar.monthrange(year,month)[1])
    return date(year,month,day)

def query(compares, targets, between, by, chartMode, barMode, pieMode, sortBy, jsonfile):
    # compares = ['assigned', 'not_assigned', 'guest']
    # target = {"m":"","h":"","s": "","p":""}
    # between = 'masseur'|'helper'|'shop'|'date'
    conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='d4sg', db='d4sg_vim',charset='utf8')
    cur = conn.cursor()
    cur.execute("SELECT wid,mid,hid,sid,assigned,not_assigned,guest_num,log_date FROM worklog")  
    worklogs = list(cur.fetchall())
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
    timeSeriesPlot = False
    fromDate = None
    toDate = None
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
    if targets["p"] != None:
        if chartMode == "line":
            timeSeriesPlot = True
        fromDate = targets["p"]["from"].date()
        toDate = targets["p"]["to"].date()
        worklogs = filter(lambda x: x[7] >= fromDate and x[7] <= toDate, worklogs)
    
# between condition
#     dataList = [
#         (TARGET_NAME,[ WORKLOGS ])
#     ]
    dataList = []
    if between == "masseur":
        for masseur in target_masseurs:
            mid = masseur[0]
            logs = filter(lambda x: x[1] == mid ,worklogs)
            if len(logs) == 0:
                continue;
            else:
                dataList.append((masseur[1],logs))
    elif between == "helper":
        for helper in target_helpers:
            hid = helper[0]
            logs = filter(lambda x: x[2] == hid ,worklogs)
            if len(logs) == 0:
                continue;
            else:
                dataList.append((helper[1],logs))
    elif between == "shop":
        for shop in target_shops:
            sid = shop[0]
            logs = filter(lambda x: x[3] == sid ,worklogs)
            if len(logs) == 0:
                continue;
            else:
                dataList.append((shop[1],logs))
    else:
#     for time period
#     dataList = [
#         (TIME_PERIOD,[ WORKLOGS ])
#     ]
        fromDate = between["from"].date()
        toDate = between["to"].date()
        if between["step"] == "week":
            step = timedelta(days=7)
            to = fromDate + step
            while to <= toDate:
                dateRange = fromDate.strftime("%Y-%m-%d")+'至'+(to-timedelta(days=1)).strftime("%Y-%m-%d")
                logs = filter(lambda x: x[7] >= fromDate and x[7] < to, worklogs)
                if len(logs) == 0:
                    fromDate = to
                    to = to + step
                    continue;   
                else:
                    dataList.append((dateRange,logs))
                    fromDate = to
                    to = to + step
        elif between["step"] == "month":
            while fromDate.month <= toDate.month:
                month = fromDate.strftime("%Y年%m月")
                logs = filter(lambda x: x[7].month == fromDate.month,worklogs)
                fromDate = add_months(fromDate,1)
                if len(logs) == 0:
                    continue;
                else:
                    dataList.append((month,logs))
        elif between["step"] == "season":
            while fromDate.month <= toDate.month:
                to = add_months(fromDate,2)
                monthRange = fromDate.strftime("%Y年%m月") +'至'+to.strftime("%Y年%m月")
                logs = filter(lambda x: x[7].month >= fromDate.month and x[7].month < to.month, worklogs)
                fromDate = add_months(fromDate,3)
                if len(logs) == 0:
                    continue;
                else:
                    dataList.append((monthRange,logs))
    # compare result and create plot 
    data = []
    layout = None
    if timeSeriesPlot:
        # line chart
        daynum = fromDate - toDate
        x = [toDate + timedelta(days=num) for num in range(daynum.days, 1)]
        for name, logs in dataList:
            y = None
            
            if by == "sum":
                for compare in compares:
                    y = [(date,reduce(apply_lambda(compares), grp, 0)) for date, grp in itertools.groupby(logs, key=lambda x: x[7])]
            elif by == "count":
                y = [(date,len(list(grp))) for date, grp in itertools.groupby(logs, key=lambda x: x[7])]
            elif by == "average":
                y = [(date, list(grp))for date, grp in itertools.groupby(logs, key=lambda x: x[7])]
                y = [(date,reduce(apply_lambda(compares), grp, 0)/float(len(grp))) if len(grp) != 0 else (date, 0) for date, grp in y]
            
            
            for d in x: 
                if not d in [date for date,value in y]:
                    y.append((d, 0))
            y.sort(key=lambda a: a[0], reverse=False)
            trace = Scatter(
                    x = x,
                    y = [v for d,v in y],
                    name = name
            )
            data.append(trace)
        layout = dict(
            xaxis = dict(
                tickformat = "%Y年%m月%d日"
            ),
            yaxis=dict(
                title='+'.join(map(lambda x: EnglishToChinese[x], compares)),
                titlefont=dict(
                    family='Arial, sans-serif',
                    size=14,
                    color='black'
                ),
            )
        )
        fig = Figure(data=data, layout=layout)
        plot_html, plotdivid, width, height = _plot_html(fig, False, "", True, '100%', 525, False)
        print plot_html

    else:
        # tmp = [(TARGET_NAME,{COMPARE:[VALUES]})]
        tmp = []
        for name, logs in dataList:
            values = {}
            for compare in compares:
                if by == "sum":
                    values[compare] = reduce(apply_lambda([compare]),logs , 0)
                elif by == "count":
                    values[compare] = len(logs)
                elif by == "average":
                    if len(logs) != 0:
                        values[compare] = reduce(apply_lambda([compare]),logs , 0)/float(len(logs))
            tmp.append((name, values))
        # Sort List by SORTBY
        if sortBy != None:
            if sortBy in compares:
                tmp = sorted(tmp, key=lambda a: a[1][sortBy], reverse=True)
            elif sortBy == "assigned_not_assigned":
                tmp = sorted(tmp, key= lambda a: a[1]["assigned"]+a[1]["not_assigned"], reverse=True)
        if jsonfile:
            jsonArray = []
            fieldname = ""
            if between == "masseur" or between == "helper":
                fieldname = "人名"
            elif between == "店名":
                fieldname = "店名"
            else:
                fieldname = "時間"
            for item in tmp:
                obj = {}
                obj[fieldname] = item[0]
                for compare in compares:
                    obj[compare] = item[1][compare]
                jsonArray.append(obj)
            print json.dumps(jsonArray,sort_keys=True, indent=4, separators=(',', ': '))
        else:
            x = map(lambda x: x[0], tmp)
            y = {}
            for compare in compares:
                y[compare] = map(lambda a: a[1][compare], tmp)

            if chartMode == "pie":
                if pieMode == "sum" and"assigned" in compares and "not_assigned" in compares:
                    length = len(y["assigned"])
                    values = []
                    for i in range(0, length):
                        values.append(y["assigned"][i]+y["not_assigned"][i])
                    data.append({
                        "labels":x,
                        "values":values,
                        "type":"pie",
                        "name":EnglishToChinese["assigned_not_assigned"],
                        "hoverinfo":"label+percent+name"
                    })
                    layout = {}
                elif pieMode == "split":
                    iterator = 0
                    for compare in compares:
                        iterator += 1 
                        xLeft = float(1)/len(compares) * (iterator-1)
                        xRight = float(1)/len(compares) * iterator
                        data.append({
                            "labels":x,
                            "values":y[compare],
                            "type":"pie",
                            "name":EnglishToChinese[compare],
                            "domain": {'x': [xLeft, xRight],
                               'y': [0,0.8]},
                            "hoverinfo":"label+percent+name"
                        })
                    layout = {}
            elif chartMode == "bar":
                for compare in compares:
                    trace = Bar(
                        x = x,
                        y = y[compare],
                        name = EnglishToChinese[compare]
                    )
                    data.append(trace)
                layout = Layout(
                    barmode= barMode
                )

            fig = Figure(data=data, layout=layout)
            plot_html, plotdivid, width, height = _plot_html(fig, False, "", True, '100%', 525, False)
    #         plotly.offline.plot(fig)
            print plot_html
    cur.close()
    conn.close()
    
parser = argparse.ArgumentParser(description='Analyze ')
parser.add_argument("--compare", help="Compare attributes, including assigned, not_assigned, guest. ex.: --compare assigned,not_assigned")
parser.add_argument("--fromDate", help="From date for target's period or between's period. ex.: --from 2015-04-01")
parser.add_argument("--toDate", help="Last date for target's period. ex.: --to 2015-04-01")
parser.add_argument("--step", help="Select one time step format for between's period, including week, month, season. ex.: --step season", default="")

parser.add_argument("--between", help="Select one between attributes, including masseur, helper, shop. ex.: --between masseur", default="masseur")

parser.add_argument("--masseur", help="Target masseur Name", default="")
parser.add_argument("--helper", help="Target helper Name", default="")
parser.add_argument("--shop", help="Target shop Name", default="")

parser.add_argument("--by", help="Select one aggregate argument, including sum, count, average. ex.: --by sum", default="sum")
parser.add_argument("--chartMode", help="Select one chart mode , including bar,pie,line. ex.: --chartMode pie", default="bar")
parser.add_argument("--barMode", help="Select one bar chart mode , including stack,group. ex.: --barMode group", default="group")
parser.add_argument("--pieMode", help="Select one bar pie mode , including sum, split. ex.: --pieMode sum", default="sum")

parser.add_argument("--sortBy", help="Sort bar chart by COMPARE, assigned_not_assigned. ex.: --sortBy assigned_not_assigned")
parser.add_argument("--jsonfile", help="output json file.", action='store_true')

args = parser.parse_args()
if args.compare != None:
    compares = args.compare.split(',')
target_period = None
between = args.between
step = args.step

if step != "":
    between = {"from":datetime.strptime(args.fromDate,"%Y-%m-%d"), "to":datetime.strptime(args.toDate,"%Y-%m-%d"), "step":step}
elif args.fromDate != None and args.toDate != None:
    target_period = {"from":datetime.strptime(args.fromDate,"%Y-%m-%d"), "to":datetime.strptime(args.toDate,"%Y-%m-%d")}
query(compares, {"m":args.masseur,"h":args.helper,"s":args.shop,"p":target_period}, between, args.by, args.chartMode, args.barMode, args.pieMode, args.sortBy, args.jsonfile)


# In[ ]:



