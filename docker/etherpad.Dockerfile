FROM etherpad/etherpad:latest

ARG ETHERPAD_PLUGINS="ep_comments_page ep_author_neat ep_plantuml ep_tables4 ep_table_of_contents ep_font_size ep_headings2"
ENV PLUGINS="ep_comments_page ep_author_neat ep_plantuml ep_tables4 ep_table_of_contents ep_font_size ep_headings2"