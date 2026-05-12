if ((v['receiver_address'] is List && (v['receiver_address'] as List).isNotEmpty) ||
                      (v['delivery_point'] is List && (v['delivery_point'] as List).isNotEmpty))
                    Padding(
                      padding: EdgeInsets.only(left: Dim().d12, top: Dim().d8),
                      child: FixedTimeline.tileBuilder(
                        mainAxisSize: MainAxisSize.min,
                        verticalDirection: VerticalDirection.down,
                        theme: TimelineThemeData(
                          indicatorTheme:
                              IndicatorThemeData(size: Dim().d8, position: 0),
                          color: Clr().primaryColor,
                          connectorTheme: ConnectorThemeData(
                            color: Clr().primaryColor.withOpacity(0.4),
                            thickness: 2.0,
                            indent: 3.0,
                          ),
                          indicatorPosition: 0,
                          nodePosition: 0,
                        ),
                        builder: TimelineTileBuilder.connectedFromStyle(
                          connectorStyleBuilder: (context, index) {
                            return ConnectorStyle.dashedLine;
                          },
                          indicatorStyleBuilder: (context, index) {
                            return IndicatorStyle.dot;
                          },
                          contentsAlign: ContentsAlign.basic,
                          oppositeContentsBuilder: (context, index) =>
                              const SizedBox.shrink(),
                          lastConnectorStyle: ConnectorStyle.transparent,
                          firstConnectorStyle: ConnectorStyle.transparent,
                          contentsBuilder: (context, index) {
                            return Padding(
                              padding: EdgeInsets.only(
                                  left: Dim().d12, bottom: Dim().d12),
                              child: Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Expanded(
                                    child: Wrap(
                                      crossAxisAlignment:
                                          WrapCrossAlignment.center,
                                      children: [
                                        Column(
                                          crossAxisAlignment:
                                              CrossAxisAlignment.start,
                                          children: [
                                            Text(
                                              v['type'] == 'Billing'
                                                  ? 'Stop ${(index + 1)}'
                                                  : 'To',
                                              style: Sty().mediumText.copyWith(
                                                    color: Clr().textcolor,
                                                    fontWeight: FontWeight.w600,
                                                  ),
                                            ),
                                            SizedBox(
                                              height: Dim().d4,
                                            ),
                                            Text(
                                              v['type'] == 'Billing'
                                                  ? (v['delivery_point'] is List && index < (v['delivery_point'] as List).length
                                                      ? v['delivery_point'][index]?.toString() ?? ''
                                                      : '')
                                                  : () {
                                                      if (v['receiver_address'] is! List || index >= (v['receiver_address'] as List).length) return '';
                                                      final addr = v['receiver_address'][index];
                                                      if (addr is! Map) return '';
                                                      return '${addr['city'] ?? ''} ${addr['pincode'] ?? ''}'.trim();
                                                    }(),
                                              maxLines: 3,
                                              overflow: TextOverflow.ellipsis,
                                              style: Sty().smallText.copyWith(
                                                  color: Clr().primaryColor),
                                            ),
                                          ],
                                        ),
                                      ],
                                    ),
                                  ),
                                  v['type'] == 'Billing'
                                      ? Container()
                                      : InkWell(
                                          onTap: () {
                                            if (v['receiver_address'] is! List || index >= (v['receiver_address'] as List).length) return;
                                            final addr = v['receiver_address'][index];
                                            if (addr is! Map) return;
                                            final lat = double.tryParse(addr['latitude']?.toString() ?? '');
                                            final lng = double.tryParse(addr['longitude']?.toString() ?? '');
                                            if (lat != null && lng != null) {
                                              MapsLauncher.launchCoordinates(lat, lng);
                                            }
                                          },
                                          child: Column(
                                            mainAxisAlignment:
                                                MainAxisAlignment.end,
                                            children: [
                                              Padding(
                                                padding:
                                                    EdgeInsets.only(right: 10),
                                                child: Text(
                                                  "View Map",
                                                  style: Sty()
                                                      .smallText
                                                      .copyWith(
                                                          height: 1.2,
                                                          color: Clr()
                                                              .primaryColor,
                                                          fontWeight:
                                                              FontWeight.w500,
                                                          decoration:
                                                              TextDecoration
                                                                  .underline,
                                                          decorationColor: Clr()
                                                              .primaryColor),
                                                ),
                                              )
                                            ],
                                          ),
                                        )
                                ],
                              ),
                            );
                          },
                          itemCount: v['type'] == 'Billing'
                              ? (v['delivery_point'] is List ? (v['delivery_point'] as List).length : 0)
                              : (v['receiver_address'] is List ? (v['receiver_address'] as List).length : 0),
                        ),
                      ),
                    ),
